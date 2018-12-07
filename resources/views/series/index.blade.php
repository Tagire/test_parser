@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Сериалы</h2>
                <div class="panel panel-default">
                    <div class="panel-heading">Поиск</div>
                    <div class="panel-body">
                        <form method="GET" action="{{ url('/') }}" accept-charset="UTF-8" class="navbar-form navbar-right" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{$search}}">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">
                                        Search
                                    </button>
                                </span>
                            </div>
                        </form>

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
									<tr>
                                        <th>Сериал(ру)</th>
                                        <th>Название серии(ру)</th>
                                        <th>Название серии(анг)</th>
                                        <th>Дата выхода(ру)</th>
                                        <th>Дата выхода(анг)</th>
                                        <th>Подробнее</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($episodes as $episode)
                                    <tr>
                                        <td>{{ $episode->series->name }}</td>
                                        <td>{{ $episode->name_ru }}</td>
                                        <td>{{ $episode->name_en }}</td>
                                        <td>{{ $episode->release_date_ru }}</td>
                                        <td>{{ $episode->release_date_en }}</td>
                                        <td><a href="{{ $episode->details_link }}">Подробнее</a></td>
									</tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $episodes->appends([
                                'search' => Request::get('search'),
                                ])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
