<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $election->title }} - Results</title>
    <style>
        body { font-family: 'Inter', sans-serif; color: #0a1628; margin: 40px; }
        h1 { font-size: 28px; margin-bottom: 5px; }
        h2 { font-size: 20px; margin-top: 30px; border-bottom: 2px solid #f97316; padding-bottom: 5px; }
        .meta { color: #64748b; font-size: 14px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; padding: 8px; background: #f1f5f9; font-size: 13px; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        .winner { color: #16a34a; font-weight: bold; }
        .stats { display: flex; gap: 30px; margin: 20px 0; }
        .stat-box { background: #f8fafc; padding: 15px 25px; border-radius: 8px; text-align: center; }
        .stat-box .label { font-size: 12px; color: #64748b; }
        .stat-box .value { font-size: 24px; font-weight: bold; color: #0a1628; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <h1>{{ $election->title }}</h1>
    <div class="meta">
        Status: {{ ucfirst($election->status) }} |
        {{ $election->starts_at->format('M d, Y') }} - {{ $election->ends_at->format('M d, Y') }}
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="label">Total Voters</div>
            <div class="value">{{ $results['total_voters'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Votes Cast</div>
            <div class="value">{{ $results['total_votes_cast'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Turnout</div>
            <div class="value">{{ $results['turnout_percentage'] }}%</div>
        </div>
    </div>

    @foreach ($results['positions'] as $positionResult)
        <h2>{{ $positionResult['position']['title'] }}</h2>
        <p style="font-size: 14px; color: #64748b;">
            Total Votes: {{ $positionResult['total_votes'] }} | Abstentions: {{ $positionResult['abstentions'] }}
        </p>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Candidate</th>
                    <th>Votes</th>
                    <th>Percentage</th>
                    <th>Winner</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($positionResult['candidates'] as $c)
                    <tr>
                        <td>{{ $c['rank'] }}</td>
                        <td>{{ $c['candidate']->user->name }}</td>
                        <td>{{ $c['vote_count'] }}</td>
                        <td>{{ $c['vote_percentage'] }}%</td>
                        <td class="{{ $c['is_winner'] ? 'winner' : '' }}">{{ $c['is_winner'] ? 'Yes' : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="footer">
        Generated on {{ now()->format('M d, Y H:i') }} | Cornelect Election System
    </div>
</body>
</html>
