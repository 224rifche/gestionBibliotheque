<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\Penalite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpruntController extends Controller
{
    private function refreshRetards(?int $userId = null): void
    {
        $query = Emprunt::query()
            ->whereNull('date_retour_effective')
            ->where('statut', 'en_cours')
            ->whereDate('date_retour_prevue', '<', Carbon::today());

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        $query->update(['statut' => 'en_retard']);
    }

    public function demander(Livre $livre)
    {
        $user = auth()->user();

        if (($livre->exemplaires_disponibles ?? 0) <= 0) {
            return redirect()->back();
        }

        $deja = Emprunt::query()
            ->where('user_id', $user->id)
            ->where('livre_id', $livre->id)
            ->whereIn('statut', ['demande', 'en_cours', 'en_retard'])
            ->exists();

        if ($deja) {
            return redirect()->route('emprunts.mes');
        }

        Emprunt::create([
            'user_id' => $user->id,
            'livre_id' => $livre->id,
            'date_emprunt' => Carbon::today(),
            'date_retour_prevue' => Carbon::today()->addDays(14),
            'date_retour_effective' => null,
            'statut' => 'demande',
        ]);

        return redirect()->route('emprunts.mes');
    }

    public function mesEmprunts()
    {
        $this->refreshRetards(auth()->id());

        $emprunts = Emprunt::query()
            ->with(['livre.categorie', 'livre.auteurs', 'penalite'])
            ->where('user_id', auth()->id())
            ->orderByDesc('id')
            ->get();

        return view('emprunts.mes', compact('emprunts'));
    }

    public function demandes()
    {
        $emprunts = Emprunt::query()
            ->with(['user', 'livre.categorie'])
            ->where('statut', 'demande')
            ->orderBy('created_at')
            ->get();

        return view('emprunts.demandes', compact('emprunts'));
    }

    public function valider(Emprunt $emprunt)
    {
        if ($emprunt->statut !== 'demande') {
            return redirect()->back();
        }

        DB::transaction(function () use ($emprunt) {
            $empruntLocked = Emprunt::query()->lockForUpdate()->find($emprunt->id);

            if (! $empruntLocked || $empruntLocked->statut !== 'demande') {
                return;
            }

            $livre = Livre::query()->lockForUpdate()->find($empruntLocked->livre_id);

            if (! $livre) {
                return;
            }

            if (($livre->exemplaires_disponibles ?? 0) <= 0) {
                return;
            }

            $nouveauDisponible = $livre->exemplaires_disponibles - 1;

            $livre->update([
                'exemplaires_disponibles' => $nouveauDisponible,
                'disponible' => $nouveauDisponible > 0,
            ]);

            $empruntLocked->update([
                'statut' => 'en_cours',
            ]);
        });

        return redirect()->back();
    }

    public function enCours()
    {
        $this->refreshRetards();

        $emprunts = Emprunt::query()
            ->with(['user', 'livre.categorie'])
            ->whereNull('date_retour_effective')
            ->whereIn('statut', ['en_cours', 'en_retard'])
            ->orderBy('date_retour_prevue')
            ->get();

        return view('emprunts.en_cours', compact('emprunts'));
    }

    public function validerRetour(Emprunt $emprunt)
    {
        if (! in_array($emprunt->statut, ['en_cours', 'en_retard'], true)) {
            return redirect()->back();
        }

        $today = Carbon::today();
        $daysLate = 0;

        if ($emprunt->date_retour_prevue && $today->gt(Carbon::parse($emprunt->date_retour_prevue))) {
            $daysLate = Carbon::parse($emprunt->date_retour_prevue)->diffInDays($today);
        }

        DB::transaction(function () use ($emprunt, $today, $daysLate) {
            $empruntLocked = Emprunt::query()->lockForUpdate()->find($emprunt->id);

            if (! $empruntLocked || ! in_array($empruntLocked->statut, ['en_cours', 'en_retard'], true)) {
                return;
            }

            $empruntLocked->update([
                'date_retour_effective' => $today,
                'statut' => 'retourne',
            ]);

            if ($daysLate > 0) {
                Penalite::updateOrCreate(
                    ['emprunt_id' => $empruntLocked->id],
                    ['montant' => $daysLate * 500, 'payee' => false]
                );
            }

            $livre = Livre::query()->lockForUpdate()->find($empruntLocked->livre_id);

            if (! $livre) {
                return;
            }

            $nouveauDisponible = min(
                (int) $livre->nombre_exemplaires,
                (int) ($livre->exemplaires_disponibles ?? 0) + 1
            );

            $livre->update([
                'exemplaires_disponibles' => $nouveauDisponible,
                'disponible' => $nouveauDisponible > 0,
            ]);
        });

        return redirect()->back();
    }

    public function retards()
    {
        $this->refreshRetards();

        $emprunts = Emprunt::query()
            ->with(['user', 'livre.categorie', 'penalite'])
            ->whereNull('date_retour_effective')
            ->where('statut', 'en_retard')
            ->orderBy('date_retour_prevue')
            ->get();

        return view('emprunts.retards', compact('emprunts'));
    }
}
