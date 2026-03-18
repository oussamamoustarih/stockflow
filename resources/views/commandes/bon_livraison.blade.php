<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon de Livraison</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #1e293b; font-size: 24px; margin: 0; }
        .header p { color: #64748b; margin: 5px 0; }
        .info-section { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info-section div { width: 48%; }
        .info-section h4 { color: #1e293b; border-bottom: 2px solid #22c55e; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { background-color: #1e293b; color: #fff; padding: 8px; text-align: left; }
        table td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
        table tr:nth-child(even) { background-color: #f8fafc; }
        .signature { margin-top: 60px; display: flex; justify-content: space-between; }
        .signature div { width: 45%; text-align: center; }
        .signature .ligne { border-top: 1px solid #333; margin-top: 40px; padding-top: 5px; }
        .footer { margin-top: 40px; text-align: center; color: #94a3b8; font-size: 11px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>BON DE LIVRAISON</h1>
        <p><strong>{{ $commande->bonLivraison->numero_bl }}</strong></p>
        <p>Date de livraison : {{ \Carbon\Carbon::parse($commande->bonLivraison->date_livraison)->format('d/m/Y') }}</p>
    </div>

    <div class="info-section">
        <div>
            <h4>Expéditeur</h4>
            <p><strong>StockFlow</strong></p>
        </div>
        <div>
            <h4>Destinataire</h4>
            <p><strong>{{ $commande->client->nom }} {{ $commande->client->prenom }}</strong></p>
            <p>{{ $commande->client->telephone ?? '' }}</p>
            <p>{{ $commande->client->adresse ?? '' }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Produit</th>
                <th>Référence</th>
                <th>Quantité livrée</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commande->lignes as $index => $ligne)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $ligne->produit->libelle ?? '-' }}</td>
                <td>{{ $ligne->produit->reference ?? '-' }}</td>
                <td>{{ $ligne->quantite }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <div>
            <p>Signature du livreur :</p>
            <div class="ligne">Nom et signature</div>
        </div>
        <div>
            <p>Signature du client :</p>
            <div class="ligne">Nom et signature</div>
        </div>
    </div>

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>