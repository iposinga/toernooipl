<table class="table table-sm table-hover">
    <thead>
    <tr><th class="border-end"></th><th class="text-center border-end">team</th><th class="border-end">naam</th><th class="text-center">p</th><th class="text-center border-end">g</th><th class="text-center">w</th><th class="text-center">g</th><th class="text-center border-end">v</th><th colspan="3" class="text-center">doelsaldo</th></tr>
    </thead>
    <?php $plek = 1 ?>
    @foreach($sortedteams as $sortedteam)
            <tr>
            <td class="text-end border-end">{{ $plek }}.</td>
            <td class="text-end border-end" style="padding-right: 15px;">{{ $sortedteam->team_nr }}</td>
            <td>{{ $sortedteam->team_name }}</td>
            <td class="text-end text-bg-primary">{{ $sortedteam->punten }}</td>
            <td class="text-end border-end">{{ $sortedteam->gespeeld }}</td>
            <td class="text-end">{{ $sortedteam->gewonnen }}</td>
            <td class="text-end">{{ $sortedteam->gelijk }}</td>
            <td class="text-end border-end">{{ $sortedteam->verloren }}</td>
            <td class="text-end">+{{ $sortedteam->doelpvoor }}</td>
            <td class="text-end">-{{ $sortedteam->doelptegen }}</td>
            <td class="text-end text-bg-info">{{ $sortedteam->doelsdaldo }}</td>
        </tr>
    <?php $plek++; ?>
    @endforeach
    </tbody>
</table>
