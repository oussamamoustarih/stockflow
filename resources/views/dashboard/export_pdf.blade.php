<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport du tableau de bord</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; }
        .header { text-align: center; margin-bottom: 24px; }
        .header h1 { margin: 0 0 8px; font-size: 24px; }
        .header p { margin: 0; color: #4b5563; }
        .section { margin-bottom: 20px; }
        .section h2 { margin-bottom: 10px; font-size: 18px; border-bottom: 1px solid #d1d5db; padding-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        th, td { border: 1px solid #d1d5db; padding: 8px 10px; font-size: 12px; }
        th { background: #f8fafc; text-align: left; }
        .small { color: #6b7280; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport global du tableau de bord</h1>
        <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <h2>Résumé des principaux indicateurs</h2>
        <table>
            <thead>
                <tr>
                    <th>Indicateur</th>
                    <th>Valeur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($metrics as $label => $value)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Top 5 produits vendus</h2>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité vendue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProduits as $ligne)
                <tr>
                    <td>{{ $ligne->produit->libelle ?? '-' }}</td>
                    <td>{{ $ligne->total_vendu }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="small">Aucun produit vendu.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Top 10 meilleurs clients</h2>
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>CA total (DH)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topClients as $client)
                <tr>
                    <td>{{ trim(($client->nom ?? '-') . ' ' . ($client->prenom ?? '-')) }}</td>
                    <td>{{ number_format($client->ventes_sum_montant_total ?? 0, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="small">Aucun client.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Clients inactifs (+ 60 jours)</h2>
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Téléphone</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientsInactifs as $client)
                <tr>
                    <td>{{ trim(($client->nom ?? '-') . ' ' . ($client->prenom ?? '-')) }}</td>
                    <td>{{ $client->telephone ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="small">Aucun client inactif.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Produits en alerte</h2>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Stock actuel</th>
                    <th>Seuil alerte</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produitsAlerte as $produit)
                <tr>
                    <td>{{ $produit->libelle }}</td>
                    <td>{{ $produit->quantite_stock }}</td>
                    <td>{{ $produit->seuil_alerte }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="small">Aucun produit en alerte.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
