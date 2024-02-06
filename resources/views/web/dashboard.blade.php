@extends('layouts.app',['pageSlug' => 'dashboard'])

@section('content')
<div>
    <nav>
        <div class="nav nav-tabs">
            @foreach($rec2 as $ky => $val)
            <button class="nav-link btn btn-info dash-btn" id="nav-{{$val->name}}-tab" data-toggle="tab" data-target="#nav-{{$val->name}}" type="button" role="tab">
                {{$val->name}}
            </button>
            @endforeach
            <button class="nav-link btn btn-info dash-btn" data-toggle="modal" data-target="#add">
                +
            </button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="row demi">
            <div>
                <button id="drag" class="btn btn-info" data-temp="lock">
                    <i class="fa-solid fa-lock"></i>
                </button>
            </div>
        </div>
        @foreach($rec2 as $ky => $val)
        <div class="tab-pane fade drawpad dashboard @if($ky == 0 ){{ 'active show' }}@endif" id="nav-{{$val->name}}">
        </div>
        @endforeach
    </div>
    @include('modal.add',['heading'=>'Sheets'])
</div>
@endsection

@push('js')
<script>
    formify('modal_body', [
        ["text", "add_Sheet", "name"]
    ]);
    formify('sensor_add', [
        ["text", "add_Sensor", "name"],
        ["text", "add_sens_Sheet", "sheet"],
        ["text", "add_svg", "svg"],
    ]);
    apitoken = $('#api-token')[0].value;

    function dummy(event, ui) {
        $.ajax({
            url: "/api/sensor_pos",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + apitoken
            },
            data: {
                "id": event.target.id,
                "x": ui.position.left,
                "y": ui.position.top,
            }
        })
    }
    setInterval(fetch_dashboard, 60000);
    fetch_dashboard();

    function fetch_dashboard() {
        var comp = $('.active.drawpad.dashboard.show')[0];
        var name = comp.id.replace('nav-', '');
        $.ajax({
            url: "/api/dashboard_component",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + apitoken
            },
            data: {
                "name": name
            },
            success: (e) => {
                html = "";
                e.forEach((item, index, arr) => {
                    html += "<div id=" + item.name + " class='draggable col-md-1' style='--l:" + item.ui_x + "px;--t:" + item.ui_y + "px;'>";
                    html += "<div>" + item.svg + "</div>";
                    if (item.value !== "") {
                        html += "<div>";
                        html += "<label title=" + item.name + ">";
                        var objx = JSON.parse(item.value);
                        for (var key in objx) {
                            html += key;
                            html += ":";
                            html += objx[key];
                            html += "</br>";
                        }
                        html += "</label>";
                        html += "</div>";
                    }
                    html += "</div>"
                });
                comp.innerHTML = html;
            }
        })
    }
    $('#save').click((e) => {
        e.preventDefault();
        var sheet = $("#add_Sheet").val();
        console.log(sheet);
        $.ajax({
            url: "/api/sheet",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + apitoken,
            },
            data: {
                "sheet": sheet,
            },
            success: (e) => {
                location.reload();
            }
        });
    });
    $('#drag').click((e)=>{
        e.preventDefault();
        if(e.currentTarget.dataset.temp == 'lock')
        {
            e.currentTarget.dataset.temp = 'unlock';
            e.currentTarget.innerHTML='<i class="fa-solid fa-lock-open"></i>';
            $('.draggable').draggable({
                containment: "parent",
                stop: (event, ui) => {
                    dummy(event, ui);
                }
            });
        }
        else
        {
            e.currentTarget.dataset.temp = 'lock';
            e.currentTarget.innerHTML='<i class="fa-solid fa-lock"></i>';
            $('.draggable').draggable('destroy');
        }
    })
</script>
@endpush