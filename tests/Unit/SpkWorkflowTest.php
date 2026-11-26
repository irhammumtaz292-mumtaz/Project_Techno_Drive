<?php

namespace Tests\Unit;

use App\Http\Requests\RejectSpkRequest;
use App\Http\Requests\StoreSpkRequest;
use App\Models\Spk;
use App\Models\User;
use App\Policies\SpkPolicy;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class SpkWorkflowTest extends TestCase
{
    private function makeUser(string $role, int $id = 1): User
    {
        $user = new User(['name' => $role, 'email' => strtolower($role) . '@test.local', 'role' => $role]);
        $user->id = $id;

        return $user;
    }

    private function makeSpk(string $status): Spk
    {
        $spk = new Spk();
        $spk->id = 10;
        $spk->project_id = 1;
        $spk->no_spk = '001/TD/SPK/XI/2026';
        $spk->status_verifikasi = $status;

        return $spk;
    }

    public function test_spk_policy_is_auto_discovered_for_spk_model(): void
    {
        $this->assertInstanceOf(SpkPolicy::class, Gate::getPolicyFor(Spk::class));
    }

    public function test_role_normalization_and_audit_prefix_support_english_and_indonesian_aliases(): void
    {
        $this->assertSame('Logistics', Spk::normalizeVerifierRole('Logistics'));
        $this->assertSame('Logistics', Spk::normalizeVerifierRole('logistik'));
        $this->assertSame('Finance', Spk::normalizeVerifierRole('Finance'));
        $this->assertSame('Finance', Spk::normalizeVerifierRole('keuangan'));
        $this->assertSame('Director', Spk::normalizeVerifierRole('Director'));
        $this->assertSame('Director', Spk::normalizeVerifierRole('direktur'));
        $this->assertSame('', Spk::normalizeVerifierRole('Supervisor'));
        $this->assertSame('', Spk::normalizeVerifierRole('Staff'));

        $this->assertSame('logistics', Spk::auditPrefixForRole('logistik'));
        $this->assertSame('finance', Spk::auditPrefixForRole('keuangan'));
        $this->assertSame('director', Spk::auditPrefixForRole('direktur'));
        $this->assertNull(Spk::auditPrefixForRole('Staff'));
    }

    public function test_tiered_approval_transitions_enforce_strict_sequence(): void
    {
        $draft = $this->makeSpk('Draft');
        $this->assertTrue($draft->canBeVerifiedBy('Logistics'));
        $this->assertTrue($draft->canBeVerifiedBy('logistik'));
        $this->assertFalse($draft->canBeVerifiedBy('Finance'));
        $this->assertFalse($draft->canBeVerifiedBy('Director'));
        $this->assertSame('Verifikasi Logistik', $draft->nextStatusForRole('Logistics'));
        $this->assertNull($draft->nextStatusForRole('Finance'));

        $afterLogistics = $this->makeSpk('Verifikasi Logistik');
        $this->assertFalse($afterLogistics->canBeVerifiedBy('Logistics'));
        $this->assertTrue($afterLogistics->canBeVerifiedBy('Finance'));
        $this->assertTrue($afterLogistics->canBeVerifiedBy('keuangan'));
        $this->assertFalse($afterLogistics->canBeVerifiedBy('Director'));
        $this->assertSame('Verifikasi Keuangan', $afterLogistics->nextStatusForRole('Finance'));

        $afterFinance = $this->makeSpk('Verifikasi Keuangan');
        $this->assertFalse($afterFinance->canBeVerifiedBy('Logistics'));
        $this->assertFalse($afterFinance->canBeVerifiedBy('Finance'));
        $this->assertTrue($afterFinance->canBeVerifiedBy('Director'));
        $this->assertTrue($afterFinance->canBeVerifiedBy('direktur'));
        $this->assertSame('Terbit', $afterFinance->nextStatusForRole('direktur'));

        $issued = $this->makeSpk('Terbit');
        $this->assertFalse($issued->canBeVerifiedBy('Logistics'));
        $this->assertFalse($issued->canBeVerifiedBy('Finance'));
        $this->assertFalse($issued->canBeVerifiedBy('Director'));

        $cancelled = $this->makeSpk('Dibatalkan');
        $this->assertFalse($cancelled->canBeVerifiedBy('Logistics'));
        $this->assertFalse($cancelled->canBeVerifiedBy('Finance'));
        $this->assertFalse($cancelled->canBeVerifiedBy('Director'));
    }

    public function test_spk_policy_enforces_role_permissions_at_every_stage(): void
    {
        $policy = new SpkPolicy();

        $supervisor = $this->makeUser('Supervisor', 1);
        $logistics  = $this->makeUser('Logistics', 2);
        $finance    = $this->makeUser('Finance', 3);
        $director   = $this->makeUser('direktur', 4);
        $staff      = $this->makeUser('Staff', 5);

        $this->assertTrue($policy->viewAny($supervisor));
        $this->assertTrue($policy->viewAny($logistics));
        $this->assertTrue($policy->viewAny($finance));
        $this->assertTrue($policy->viewAny($director));
        $this->assertFalse($policy->viewAny($staff));

        $this->assertTrue($policy->create($supervisor));
        $this->assertFalse($policy->create($logistics));
        $this->assertFalse($policy->create($finance));
        $this->assertFalse($policy->create($director));
        $this->assertFalse($policy->create($staff));

        $draft = $this->makeSpk('Draft');
        $this->assertTrue($policy->approve($logistics, $draft));
        $this->assertTrue($policy->reject($logistics, $draft));
        $this->assertFalse($policy->approve($supervisor, $draft));
        $this->assertFalse($policy->approve($finance, $draft));
        $this->assertFalse($policy->approve($director, $draft));
        $this->assertFalse($policy->approve($staff, $draft));

        $verLog = $this->makeSpk('Verifikasi Logistik');
        $this->assertTrue($policy->approve($finance, $verLog));
        $this->assertTrue($policy->reject($finance, $verLog));
        $this->assertFalse($policy->approve($logistics, $verLog));
        $this->assertFalse($policy->approve($director, $verLog));

        $verFin = $this->makeSpk('Verifikasi Keuangan');
        $this->assertTrue($policy->approve($director, $verFin));
        $this->assertTrue($policy->reject($director, $verFin));
        $this->assertFalse($policy->approve($finance, $verFin));
        $this->assertFalse($policy->approve($logistics, $verFin));
    }

    public function test_form_requests_define_expected_validation_rules(): void
    {
        $storeRules = (new StoreSpkRequest())->rules();
        $this->assertArrayHasKey('project_id', $storeRules);
        $this->assertArrayHasKey('tgl_spk', $storeRules);

        $rejectRules = (new RejectSpkRequest())->rules();
        $this->assertArrayHasKey('alasan_batal', $rejectRules);
        $this->assertContains('required', $rejectRules['alasan_batal']);
    }
}
