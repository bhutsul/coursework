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

if ( window.history.replaceState ) 
{
  window.history.replaceState( null, null, window.location.href );
}