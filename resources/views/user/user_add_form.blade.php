<?php
    /*echo $tournementid;
    echo "<pre>";
    print_r($users);
    echo "</pre>";*/
?>
<form id="add_users_form" class="row g-3" method="post" action="{{ route('storetournementuser', ['id' => $tournementid]) }}">
    {{ csrf_field() }}
    <div class="col-md-2">
    </div>
    <div class="col-md-8">
        <label for="inputHomeScore" class="form-label">Selecteer 1 of meer gebruikers</label>
        <select name="inputUsers[]" class="form-select mb-3" multiple size="10">
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
</form>
