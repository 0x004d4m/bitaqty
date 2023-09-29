{{-- This file is used to store topbar (right) items --}}


{{-- <li class="nav-item d-md-down-none"><a class="nav-link" href="#"><i class="la la-bell"></i><span class="badge badge-pill badge-danger">5</span></a></li>
<li class="nav-item d-md-down-none"><a class="nav-link" href="#"><i class="la la-list"></i></a></li>
<li class="nav-item d-md-down-none"><a class="nav-link" href="#"><i class="la la-map"></i></a></li> --}}
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"
        style="position: relative;width: 35px;height: 35px;margin: 0 10px;">
        <span class="backpack-avatar-menu-container"
            style="position: absolute;left: 0;width: 100%;background-color: #4942d3;border-radius: 50%;color: #FFF;line-height: 35px;font-size: 85%;font-weight: 300;">
            {{App\Models\Currency::where('id',Session::get('currency'))->first()->symbol}}
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right mr-4 pb-1 pt-1">
        @foreach (App\Models\Currency::get() as $Currency)
            <a class="dropdown-item @if ($Currency->id == Session::get('currency'))active @endif" href="{{url('set-currency/'.$Currency->id)}}">{{$Currency->symbol}} - {{$Currency->name}}</a>
        @endforeach
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"
        style="position: relative;width: 35px;height: 35px;margin: 0 10px;">
        <span class="backpack-avatar-menu-container"
            style="position: absolute;left: 0;width: 100%;background-color: #03c8d6;border-radius: 50%;color: #FFF;line-height: 35px;font-size: 85%;font-weight: 300;">
            {{Backpack\LangFileManager\app\Models\Language::where('active', 1)->where('abbr',Session::get('locale'))->first()->abbr}}
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right mr-4 pb-1 pt-1">
        @foreach (Backpack\LangFileManager\app\Models\Language::get() as $Language)
            <a class="dropdown-item @if ($Language->abbr == Session::get('locale'))active @endif" href="{{url('set-language/'.$Language->abbr)}}">{{$Language->abbr}} - {{$Language->name}}</a>
        @endforeach
    </div>
</li>
