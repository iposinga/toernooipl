@extends('../layouts.app')

@section('content')
<div class="container">
    <?php
        //echo "<pre>";
        //print_r($toernooien);
        //echo "</pre>";
        ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: #29286d; color: white;">
                    <div class="row">
                        <div class="col" style="font-size: larger">{{ __('Dashboard') }}</div>
                        <div class="col text-end">
                            <a style="text-decoration: none;" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showAddTournement()">
                                <i class="bi bi-plus-square"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (Session::get('success', false))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <table class="table table-sm table-hover w-auto">
                @foreach($toernooien as $toernooi)
                            <tr style="font-size: larger">
                                <td><a style="text-decoration: none; color: #e2007c" href="{{ route('tournement.show', ['id' => $toernooi->id, 'poule_id' => 0]) }}">{{ $toernooi->tournement_name }}</a></td>
                                <td>{{ Carbon\Carbon::parse($toernooi->tournement_date)->translatedFormat('j F Y')  }}</td>
                            </tr>
                @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
    <div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-label"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>let csrftoken = "{{ csrf_token() }}"</script>
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
