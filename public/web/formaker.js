function formify(id, arg) {
    html = "<div>";
    arg.forEach((item, index, arr) => {
        html += build_inputs(item);
    });
    html += "</div>";
    $("#" + id).html(html);
}
function build_inputs(item)
{
    if(item[0]=="text")
    {
        tmp = "<input type=" + item[0] + " id=" + item[1] + " name=" + item[2] + " class='form-control'></input>";
        return tmp
    }
}
