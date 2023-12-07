<table class="table table-sm table-hover">
    <thead><tr>
        <th class="border-end"></th>
        <th class="text-center border-end">club</th>
        <th class="border-end">naam</th>
        <th class="text-center">gem</th>
        <th class="text-center">p</th>
        <th class="text-center border-end">g</th>
        <th class="text-center">w</th>
        <th class="text-center">g</th>
        <th class="text-center border-end">v</th>
        <th colspan="3" class="text-center">doelsaldo</th>
    </tr></thead>
    @foreach($clubs as $club)
            <tr>
            <td class="text-end border-end">{{ $club->club_ranking }}.</td>
            <td class="text-end border-end" style="padding-right: 15px;">{{ $club->club_nr }}</td>
            <td>{{ $club->club_name }}</td>
            <td class="text-end text-bg-primary">{{ $club->club_average }}</td>
            <td class="text-end">{{ $club->club_points }}</td>
            <td class="text-end border-end">{{ $club->club_played }}</td>
            <td class="text-end">{{ $club->club_win }}</td>
            <td class="text-end">{{ $club->club_draw }}</td>
            <td class="text-end border-end">{{ $club->club_loss }}</td>
            <td class="text-end">+{{ $club->club_goal }}</td>
            <td class="text-end">-{{ $club->club_goalagainst }}</td>
            <td class="text-end text-bg-info">{{ $club->club_goaldifference }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
