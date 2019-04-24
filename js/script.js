boxid = 0;        
function show(state, id)
{
    if(id)
    { 
      boxid = id; 
      document.getElementById('window'+id).style.display = state;         
      document.getElementById('wrap').style.display = state; 
    }
    else
    {
      document.getElementById('window'+boxid).style.display = state;
      document.getElementById('wrap').style.display = state;
    }
}

$(document).ready(function() {
  $(".like").bind("click", function() {
  	var classNameLike = $('#like').attr('class');
    var number = $('#counter').html();
    var classNameDislike = $('#dislike').attr('class');
    var status = 0;

    if (classNameLike == "like" || classNameDislike == "like")
    {
      number++;
      $('#counter').html(number);
    }
    if (classNameLike == "like active" || classNameDislike == "like active")
    {
      number--;
      $('#counter').html(number);
    }
    $.ajax({
        type: "POST",
        url: '',
        data: {
          status:status
        },
        success: function(data) 
        {
        if (!data.error)
        { 
          $('#like').toggleClass("active");
          $('#dislike').toggleClass("active");
        }
        else
        {
          alert("error");
        }
      }
    });
  });
});

$(document).ready(function() {
  $(".deleteComment").bind("click", function() {
    var link = $(this);
    if (confirm("Ви дійсно бажаєте видалити коментарій?")) 
    {
      var idDeleteComment = link.attr('id');
      parseInt(idDeleteComment,10);
    }
    $.ajax({
        type: "POST",
        url: '',
        data: {
          idDeleteComment:idDeleteComment
        },
        success: function(data) 
        {
          if (!data.error)
	        { 
	      	  location.reload(true);
          }
        else
	      {
          alert("error");
        }
      }
    });
  });
});

$(document).ready(function() {
  $(".deletePost").bind("click", function() {

    if (confirm("Ви дійсно бажаєте видалити фото?")) 
    {
      var deletePostYes = 1;
    }
    $.ajax({
        type: "POST",
        url: '',
        data: {
          deletePostYes:deletePostYes
        },
        success: function(data) 
        {
        if (!data.error)
        { 
          window.location = 'index.php?action=profile';
        }
        else
        {
          alert("error");
        }
      }
    });
  });
});

$(document).ready(function() {
  $(".deleteAvatar").bind("click", function() {

    if (confirm("Ви дійсно бажаєте видалити фото?")) 
    {
      var deleteAvatarYes = 1;
    }
    $.ajax({
        type: "POST",
        url: '',
        data: {
          deleteAvatarYes:deleteAvatarYes
        },
        success: function(data) 
        {
          if (!data.error)
          { 
            window.location = '';
          }
          else
          {
            alert("error");
          }
        }
    });
  });
});

$(document).ready(function() {
  $(".follow").bind("click", function() {
    var classNameFollow = $('#follow').attr('class');
    var classNameUnfollow = $('#unfollow').attr('class');

    var follow = 1;

    $.ajax({
        type: "POST",
        url: '',
        data: {
          follow:follow
        },
        success: function(data) 
        {
          if (!data.error)
          { 
            $('#follow').toggleClass("active");
            $('#unfollow').toggleClass("active");
            window.location = '';
          }
          else
          {
            alert("error");
          }
        }
    });
  });
});

if ( window.history.replaceState ) 
{
  window.history.replaceState( null, null, window.location.href );
}

$(document).ready(function() {
    var inProgress = false;
    var startFrom = 6;
    $(window).scroll(function() {

      if($(window).scrollTop() + $(window).height() >= $(document).height() - 50 && !inProgress) 
      {
       $.ajax({
            url: './loginviews/foto.php',
            method: 'POST',
            data: {"startFrom" : startFrom},
            beforeSend: function() 
            {
              inProgress = true;
            }
            }).done(function(data)
            {
              id1 = data.indexOf('<JSON>')+6;
              id2 = data.indexOf('</JSON>');
              datas = data.substring(id1,id2);
              var res = jQuery.parseJSON(datas);

              if (res.length > 0) 
              {  
                $.each(res, function(index, data)
                {
                    $("#foto").append(
                      "<div style = \" margin: 2%;\"><a href = \"index.php?action=viewpost&idPost="+ res[index].image_id + "\"><img src = \"./fotopost/" + res[index].image_name + 
                           "\" width = \"300px\" height = \"300px\" style = \" object-fit: cover; \"></a></div>");
                });

                inProgress = false;
                startFrom += 6;
              }
            });
        }
    });
});

$(document).ready(function() {
    var inProgress = false;
    var fotoStartFrom = 6;
    $(window).scroll(function() {

      if($(window).scrollTop() + $(window).height() >= $(document).height() - 50 && !inProgress) 
      {
       $.ajax({
            url: './loginviews/viewuserfotos.php',
            method: 'POST',
            data: {"fotoStartFrom" : fotoStartFrom},
            beforeSend: function() 
            {
              inProgress = true;
            }
            }).done(function(data)
            {
              id3 = data.indexOf('<JSON>')+6;
              id4 = data.indexOf('</JSON>');
              datas = data.substring(id3,id4);
              var resViewUser = jQuery.parseJSON(datas);

              if (resViewUser.length > 0) 
              {  
                $.each(resViewUser, function(index, data)
                {
                    $("#fotoUser").append(
                      "<div style = \"margin: 2%;\"><a href = \"index.php?action=viewuserpost&idPost="+ resViewUser[index].image_id + "\"><img src = \"./fotopost/" + resViewUser[index].image_name + 
                           "\" width = \"300px\" height = \"300px\" style = \" object-fit: cover; margin-top: 20px;\"></a></div>");
                });

                inProgress = false;
                fotoStartFrom += 6;
              }
            });
        }
    });
});

$(document).ready(function() {
    var inProgress = false;
    var newsStartFrom = 3;
    var newsEndFrom = 6;
    $(window).scroll(function() {

      if($(window).scrollTop() + $(window).height() >= $(document).height() - 50 && !inProgress) 
      {
       $.ajax({
            url: './loginviews/newsInf.php',
            method: 'POST',
            data: {
                    newsStartFrom : newsStartFrom,
                    newsEndFrom : newsEndFrom
            },
            beforeSend: function() 
            {
              inProgress = true;
            }
            }).done(function(data)
            {
              id5 = data.indexOf('<JSON>')+6;
              id6 = data.indexOf('</JSON>');
              datas = data.substring(id5,id6);
              var resViewNews = jQuery.parseJSON(datas);
              console.log(resViewNews);
              if (resViewNews.length > 0) 
              {  
                $.each(resViewNews, function(index, data)
                {
                    if (resViewNews[index] == null){
                      return false;
                    }
                    $("#news").append(
                      "<div class = \"d-flex flex-column\"><a href = \"index.php?action=viewuserprofile&idUser=" + resViewNews[index].iduser + 
                      "\"><span style = \"font-size: 4vmin;\">" + resViewNews[index].login + 
                      "</span></a><span style = \"font-size: 2vmin;\">" + resViewNews[index].date_post + 
                      "</span></div><a href = \"index.php?action=viewuserpost&idPost=" + resViewNews[index].image_id + 
                      "\"><img src = \"./fotopost/" + resViewNews[index].image_name + 
                      "\"style = \"margin-top: 20px; width:100%; max-width: 500px; min-width: 500px; height: 500px; object-fit: cover;\"></a>"
                      );
                });

                inProgress = false;
                newsStartFrom += 3;
                newsEndFrom += 3;
              }
            });
        }
    });
});