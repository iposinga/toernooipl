$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

let dragLastPlace;
let movedLastPlace;
let drag_id = "";
let moved_id = "";
let d_newid = "";
let m_newid = "";
$('.draggable').draggable({
    placeholder: 'placeholder',
    zIndex: 1000,
    containment: 'table',
    helper: function(evt) {
        let that = $(this).clone().get(0);
        $(this).hide();
        return that;
    },
    start: function(evt, ui) {
        dragLastPlace = $(this).parent();
    },
    cursorAt: {
        top: 20,
        left: 20
    },
});
$('.droppable').droppable({
    hoverClass: 'placeholder',
    drop: function(evt, ui) {
        let draggable = ui.draggable;
        let droppable = this;
        if ($(droppable).children('.draggable:visible:not(.ui-draggable-dragging)').length > 0) {
            $(droppable).children('.draggable:visible:not(.ui-draggable-dragging)').detach().prependTo(dragLastPlace);
        }
        $(draggable).detach().css({
            top: 0,
            left: 0
        }).prependTo($(droppable)).show();
        drag_id = dragLastPlace[0].innerHTML.substring(dragLastPlace[0].innerHTML.indexOf("grp_") + 4, dragLastPlace[0].innerHTML.indexOf("silvia") - 1);
        moved_id = movedLastPlace[0].innerHTML.substring(movedLastPlace[0].innerHTML.indexOf("grp_") + 4, movedLastPlace[0].innerHTML.indexOf("silvia") - 1);
        let drag = drag_id.split("_");
        let moved = moved_id.split("_");
        werkidsbij(drag[0], drag[1], drag[2], moved[0], moved[1], moved[2]);
        movedLastPlace = undefined;
    },
    over: function(evt, ui) {
        var draggable = ui.draggable;
        var droppable = this;
        if (movedLastPlace) {
            $(dragLastPlace).children().not(draggable).detach().prependTo(movedLastPlace);
        }
        if ($(droppable).children('.draggable:visible:not(.ui-draggable-dragging)').length > 0) {
            $(droppable).children('.draggable:visible').detach().prependTo(dragLastPlace);
            movedLastPlace = $(droppable);
        }
    }
})
function werkidsbij(d_gameid, d_roundid, d_pitchid, m_gameid, m_roundid, m_pitchid)
{
    $.ajax({
        url: '/aj/tournement/swap_update',
        data: {
            d_game_id: d_gameid,
            d_round_id: d_roundid,
            d_pitch_id: d_pitchid,
            m_game_id: m_gameid,
            m_round_id: m_roundid,
            m_pitch_id: m_pitchid
        },
        //dataType: 'json',
        type: 'post'
    }).done(function(){
        $("#grp_" + d_gameid + "_" + d_roundid + "_" + d_pitchid + "_silvia").attr("id", "grp_" + d_gameid + "_" + m_roundid + "_" + m_pitchid + "_silvia"); //deze doet het
        $("#grp_" + m_gameid + "_" + m_roundid + "_" + m_pitchid + "_silvia").attr("id", "grp_" + m_gameid + "_" + d_roundid + "_" + d_pitchid + "_silvia");
    }).then(function(){
        $("#grp_" + d_gameid + "_" + m_roundid + "_" + m_pitchid + "_silvia").css("background", "#C80000"); //deze doet het
        $("#grp_" + m_gameid + "_" + d_roundid + "_" + d_pitchid + "_silvia").css("background", "#C80000");
    });
}

