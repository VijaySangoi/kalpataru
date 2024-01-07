function formify(id, arg) {
    html = "<div>";
    arg.forEach((item, index, arr) => {
        html += "<div class='p-2'>";
        html += build_inputs(item);
        html += "</div>";
    });
    html += "</div>";
    $("#" + id).html(html);
}
function build_inputs(item)
{
    if(item[0]=="text")
    {
        tmp = "<input " + item[3] + " type=" + item[0] + " id=" + item[1] + " name=" + item[2] + " placeholder=" + item[2] + " class='form-control'></input>";
        return tmp
    }
}
