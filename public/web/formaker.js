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
function horiform(arg){
    html = "<div>";
    arg.forEach((row,row_index,row_arr) => {
        html += "<div class='row p-2'>";
        row.forEach((cell,cell_index,cell_arr)=>{
            html += "<div class='col "+cell[0]+"'>";
            html += build_inputs(cell[1]);
            html += "</div>";
        });
        html += "</div>";
    });
    html += "<div>";
    return html;
}
function build_inputs(item)
{
    if(item[0]=="text")
    {
        tmp = "<input " + item[3] + " type=" + item[0] + " id=" + item[1] + " name=" + item[2] + " placeholder=" + item[2] + " class='form-control'></input>";
        return tmp
    }
    if(item[0]=="select")
    {
        tmp = "<select " + item[3] + " type=" + item[0] + " id=" + item[1] + " name=" + item[2] + " placeholder=" + item[2] + " class='form-control'>";
        item[4].forEach((item,index,arr)=>{
            tmp += "<option value="+item[1]+">"+item[0]+"</option>";
        })
        tmp += "</select>";
        return tmp
    }
    if(item[0]=="html")
    {
        return item[1];
    }
}
