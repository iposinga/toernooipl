<table class="table table-sm table-hover">
    <thead><tr>
        <th class="border-end"></th>
        <th class="text-center border-end">team</th>
        <th class="border-end">naam</th>
        <th class="text-center">p</th>
        <th class="text-center border-end">g</th>
        <th class="text-center">w</th>
        <th class="text-center">g</th>
        <th class="text-center border-end">v</th>
        <th colspan="3" class="text-center">doelsaldo</th>
    </tr></thead>
    @foreach($teams as $team)
            <tr>
            <td class="text-end border-end">{{ $loop->iteration }}.</td>
            <td class="text-end border-end" style="padding-right: 15px;">{{ $team->team_nr }}</td>
            <td>{{ $team->team_name }}</td>
            <td class="text-end text-bg-primary">{{ $team->points }}</td>
            <td class="text-end border-end">{{ $team->played }}</td>
            <td class="text-end">{{ $team->win }}</td>
            <td class="text-end">{{ $team->draw }}</td>
            <td class="text-end border-end">{{ $team->loss }}</td>
            <td class="text-end">+{{ $team->goal }}</td>
            <td class="text-end">-{{ $team->goalagainst }}</td>
            <td class="text-end text-bg-info">{{ $team->goaldifference }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
