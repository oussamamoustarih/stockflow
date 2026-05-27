<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture - {{ $numeroFacture }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #1e293b; font-size: 24px; margin: 0; }
        .header p { color: #64748b; margin: 5px 0; }
        
        .facture-info { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .facture-info td { width: 50%; vertical-align: top; }
        .facture-info h4 { color: #1e293b; border-bottom: 2px solid #3b82f6; padding-bottom: 5px; margin-bottom: 10px; }
        
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.items th { background-color: #1e293b; color: #fff; padding: 8px; text-align: left; }
        table.items td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
        table.items tr:nth-child(even) { background-color: #f8fafc; }

        .total-section { float: right; width: 250px; margin-top: 10px; }
        .total-section table { width: 100%; border-collapse: collapse; }
        .total-section td { padding: 4px 8px; text-align: right; }
        .total-final { font-size: 14px; font-weight: bold; color: #1e293b; }

        /* Style spécifique pour le montant en lettres */
        .montant-lettres { 
            margin-top: 30px; 
            padding: 12px; 
            background: #f8fafc; 
            border-left: 4px solid #1e293b;
            clear: both; 
        }

        .paiement { margin-top: 20px; padding: 10px; background: #f0fdf4; border-left: 4px solid #22c55e; clear: both; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; color: #94a3b8; font-size: 11px; padding-bottom: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>FACTURE</h1>
        <p><strong>{{ $numeroFacture }}</strong></p>
        <p>Date : {{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y H:i') }}</p>
    </div>

    <table class="facture-info">
        <tr>
            <td>
                <h4>Vendeur</h4>
                <p><strong>StockFlow</strong></p>
                <p>{{ $vente->vendeur->name ?? 'OUSSAMA MOUSTARIH' }}</p>
            </td>
            <td>
                <h4>Client</h4>
                @if($vente->client)
                    <p><strong>{{ $vente->client->nom }} {{ $vente->client->prenom }}</strong></p>
                    <p>{{ $vente->client->telephone ?? '' }}</p>
                    <p>{{ $vente->client->adresse ?? '' }}</p>
                @else
                    <p>Client anonyme</p>
                @endif
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>#</th>
                <th>Produit</th>
                <th>Référence</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vente->lignes as $index => $ligne)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $ligne->produit->libelle ?? '-' }}</td>
                <td>{{ $ligne->produit->reference ?? '-' }}</td>
                <td>{{ $ligne->quantite }}</td>
                <td>{{ number_format($ligne->prix_unitaire, 2) }} DH</td>
                <td>{{ number_format($ligne->sous_total, 2) }} DH</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table>
            <tr>
                <td>Total HT :</td>
                <td>{{ number_format($vente->montant_total, 2) }} DH</td>
            </tr>
            <tr>
                <td>TVA (20%) :</td>
                <td>{{ number_format($vente->montant_total * 0.20, 2) }} DH</td>
            </tr>
            <tr class="total-final">
                <td>Total TTC :</td>
                <td>{{ number_format($totalTTC, 2) }} DH</td>
            </tr>
        </table>
    </div>

    <div class="montant-lettres">
        <strong>Arrêtée la présente facture à la somme de :</strong><br>
        <span style="font-style: italic;">{{ $totalEnLettres }}</span>
    </div>

    <div class="paiement">
        <strong>Mode de paiement :</strong> 
        {{ $vente->mode_paiement == 'especes' ? 'Espèces' : 'Carte bancaire' }}
    </div>

    <div class="footer">
        <p>Merci pour votre confiance. Document généré le {{ \Carbon\Carbon::now('Africa/Casablanca')->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>