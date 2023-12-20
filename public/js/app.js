function addEventListeners() {
    let commentCheckers = document.querySelectorAll('article.post li.comment input[type=checkbox]');
    [].forEach.call(commentCheckers, function(checker) {
      checker.addEventListener('change', sendCommentUpdateRequest);
    });
  
    let commentCreators = document.querySelectorAll('article.post form.new_comment');
    [].forEach.call(commentCreators, function(creator) {
      creator.addEventListener('submit', sendCreateCommentRequest);
    });
  
    let commentDeleters = document.querySelectorAll('article.post li a.delete');
    [].forEach.call(commentDeleters, function(deleter) {
      deleter.addEventListener('click', sendDeleteCommentRequest);
    });
  
    let postDeleters = document.querySelectorAll('article.post header a.delete');
    [].forEach.call(postDeleters, function(deleter) {
      deleter.addEventListener('click', sendDeletePostRequest);
    });
  
    let postCreator = document.querySelector('article.post button.create_new_post');
    if (postCreator != null){
      postCreator.addEventListener('click', showCreatePostForm);
      postCreator.addEventListener('submit', sendCreatePostRequest);
    }
  }
  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  
  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
  
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
  }
  
  function sendCommentUpdateRequest() {
    let comment = this.closest('li.comment');
    let id = comment.getAttribute('data-id');
    let checked = comment.querySelector('input[type=checkbox]').checked;
  
    sendAjaxRequest('post', '/api/comment/' + id, {done: checked}, commentUpdatedHandler);
  }
  
  function sendDeleteCommentRequest() {
    let id = this.closest('li.comment').getAttribute('data-id');
  
    sendAjaxRequest('delete', '/api/comment/' + id, null, commentDeletedHandler);
  }
  
  function sendCreateCommentRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
    let description = this.querySelector('input[name=description]').value;
  
    if (description != '')
      sendAjaxRequest('put', '/api/posts/' + id, {description: description}, commentAddedHandler);
  
    event.preventDefault();
  }
  
  function sendDeletePostRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
  
    sendAjaxRequest('delete', '/api/posts/' + id, null, postDeletedHandler);
  }
  
  /*function sendCreatePostRequest(event) {

    let title = document.querySelector('input[name="title"]').value;
    let description = document.querySelector('textarea[name="description"]').value;

    // Check if title is not empty and send an AJAX request
    if (title.trim() !== '' && description.trim() !== '') {
        sendAjaxRequest('put', '/api/posts/', { title: title, description: description }, postAddedHandler);
    }

    event.preventDefault();
  }*/

  function sendCreatePostRequest(event) {
    //var titleInputs = document.getElementsByName('title[]');
    var title = document.querySelector('input[name="title"]').value;
    var description = document.querySelector('textarea[name="description"]').value;
    let user_id = document.getElementById('user_id').value;

    if (title.trim() !== '' && description.trim() !== '') {
      sendAjaxRequest('put', '/api/posts', { title: title, description: description, user_id: user_id }, postAddedHandler);
    }
  /*  
    for (var i = 0; i < titleInputs.length; i++) {
        var title = titleInputs[i].value;
        if (title.trim() !== '' && description.trim() !== '') {
            sendAjaxRequest('put', '/api/posts/', { title: title, description: description }, postAddedHandler);
        }
    }*/
    event.preventDefault();
  }
  
  function commentUpdatedHandler() {
    let comment = JSON.parse(this.responseText);
    let element = document.querySelector('li.comment[data-id="' + comment.id + '"]');
    element.checked = comment.done == "true";
  }
  
  function commentAddedHandler() {
    if (this.status != 200) window.location = '/';
    let comment = JSON.parse(this.responseText);
  
    // Create the new comment
    let new_comment = createComment(comment);
  
    // Insert the new comment
    let post = document.querySelector('article.post[data-id="' + comment.post_id + '"]');
    let form = post.querySelector('form.new_comment');
    form.previousElementSibling.append(new_comment);
  
    // Reset the new comment form
    form.querySelector('[type=text]').value="";
  }
  
  function commentDeletedHandler() {
    if (this.status != 200) window.location = '/';
    let comment = JSON.parse(this.responseText);
    let element = document.querySelector('li.comment[data-id="' + comment.id + '"]');
    element.remove();
  }
  
  function postDeletedHandler() {
    if (this.status != 200) window.location = '/';
    let post = JSON.parse(this.responseText);
    let article = document.querySelector('article.post[data-id="'+ post.id + '"]');
    article.remove();
  }
  
  function postAddedHandler() {
    if (this.status != 201) window.location = '/';
    let post = JSON.parse(this.responseText);
  
    // Create the new post
    let new_post = createPost(post);
  
    // Reset the new post input
    let form = document.querySelector('#newPostForm');
    form.querySelector('[type=text]').value="";
  
    // Insert the new post
    let article = form.parentElement;
    let section = article.parentElement;
    section.insertBefore(new_post, article);
  
    // Focus on adding a comment to the new post
    new_post.querySelector('[type=text]').focus();
  }
  
  function createPost(post) {
    let new_post = document.createElement('article');
    new_post.classList.add('post');
    new_post.setAttribute('data-id', post.id);
    new_post.innerHTML = `
    <header>
        <h2><a href="posts/${post.id}">${post.name}</a></h2>
        <a href="#" class="delete">&#10761;</a>
    </header>
    <ul></ul>
    <form class="new_comment">
        <input name="title" type="text" placeholder="Title">
        <textarea name="description" placeholder="Description"></textarea>
        <button type="button" onclick="addInputField()">Add Title Input</button>
        <button type="submit" onclick="sendCreatePostRequest(event)">Submit</button>
    </form>`;
  
    let creator = new_post.querySelector('form.new_comment');
    creator.addEventListener('submit', sendCreateCommentRequest);
  
    let deleter = new_post.querySelector('header a.delete');
    deleter.addEventListener('click', sendDeletePostRequest);
  
    return new_post;
  }
  
  function createComment(comment) {
    let new_comment = document.createElement('li');
    new_comment.classList.add('comment');
    new_comment.setAttribute('data-id', comment.id);
    new_comment.innerHTML = `
    <label>
      <input type="checkbox"> <span>${comment.description}</span><a href="#" class="delete">&#10761;</a>
    </label>
    `;
  
    new_comment.querySelector('input').addEventListener('change', sendCommentUpdateRequest);
    new_comment.querySelector('a.delete').addEventListener('click', sendDeleteCommentRequest);
  
    return new_comment;
  }
  
  function addInputField() {
    let inputContainer = document.querySelector('form.new_comment');
    let newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.name = 'title[]';
    newInput.placeholder = 'Title';
    inputContainer.appendChild(newInput);
}

  addEventListeners();
  

  function toggleCreatePostForm() {
    var form = document.getElementById('create_new_post_form');
    let button = document.getElementById('create_new_post_button');
    if (form.style.display === 'none') {
        form.style.display = 'block';
        button.textContent = 'Cancel';
    } else {
        form.style.display = 'none';
        button.textContent = 'Create New Post';
    }
}

function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

function votePost(postId, voteType) {
  $.post('/posts/' + postId + '/vote/' + voteType, {
      "_token": getCsrfToken(),
  }, function(response) {
      // Handle the response
      refreshVotes(postId);
  }).fail(function(jqXHR, textStatus, errorThrown) {
      console.log("Request failed: " + textStatus + ", " + errorThrown);
  });
}


function upvotePost(postId) {
  $.post('/posts/' + postId + '/upvote', {
      "_token": getCsrfToken(),
  }, function(response) {
      // Handle the response
      refreshVotes(postId);
  }).fail(function(jqXHR, textStatus, errorThrown) {
      console.log("Request failed: " + textStatus + ", " + errorThrown);
  });
}

function downvotePost(postId) {
  $.post('/posts/' + postId + '/downvote', {
      "_token": getCsrfToken(),
  }, function(response) {
      // Handle the response
      refreshVotes(postId);
  }).fail(function(jqXHR, textStatus, errorThrown) {
      console.log("Request failed: " + textStatus + ", " + errorThrown);
  });
}



function refreshVotes(postId) {
  var baseUrl = window.location.origin;

  $.getJSON(baseUrl + '/posts/' + postId + '/upvotes', function(data) {
      $("#upvotes-count-" + postId).text(data.upvotes);
  });

  $.getJSON(baseUrl + '/posts/' + postId + '/downvotes', function(data) {
      $("#downvotes-count-" + postId).text(data.downvotes);
  });
}


function openDeleteOverlay(actionUrl) {
  document.getElementById('deletePostForm').action = actionUrl;
  document.getElementById('deletePostOverlay').style.display = 'block';
}

function closeDeleteOverlay() {
  document.getElementById('deletePostOverlay').style.display = 'none';
}

function openEditOverlay() {
  document.getElementById('editPostOverlay').style.display = 'block';
}

function closeEditOverlay() {
  document.getElementById('editPostOverlay').style.display = 'none';
}

document.getElementById('closeSidebar').onclick = function() {
  document.getElementById('sidebarMenu').style.width = '0';
}

document.getElementById('menuToggle').onclick = function() {
  var sidebar = document.getElementById('sidebarMenu');
  sidebar.style.width = sidebar.style.width === '250px' ? '0' : '250px';
}

function hideSearchResults() {
  $(".search-results").hide();
}

// Event listener for clicking outside the search results

function searchQuery() {
  var query = $("input[name='query']").val();
  if (query === '') {
    hideSearchResults();
  } else {
    // Fetch and display search results
    fetchSearchResults(query);
  }
}

function fetchSearchResults(query) {

  query = $("input[name=query]").val();
  console.log("Query: ", query); 
  
    $.ajax({
        url: ajaxSearchUrl, 
        method: 'GET',
        data: { query: query },
        success: function(response) {
            console.log("Response: ", response); 

            // Clear previous results
            $(".search-results").empty();
            showSearchResults(); 

            // Process and display the new results
            appendSearchResults(response);
        },
        error: function(xhr, status, error) {
            console.error("AJAX error: ", error); // Log any errors
        }
    });
  

}

function showSearchResults() {
  $('.search-results').show();
  if ($('.search-category:visible').length > 0) {
    $('.category-buttons').show(); // Show category buttons only if there are visible categories
  }
}




function appendSearchResults(data) {
  // Clear existing results
  $(".search-results").empty();
  var postResults = '<div class="search-category" id="posts"><h3>Posts (' + data.posts.length + ')</h3><div class="results">';
  var userResults = '<div class="search-category" id="users"><h3>Users (' + data.users.length + ')</h3><div class="results">';
  var commentResults = '<div class="search-category" id="comments"><h3>Comments (' + data.comments.length + ')</h3><div class="results">'

  // Append posts
  var postResults = '<div class="search-category"><h3>Posts</h3><div class="results">';
  if (data.posts.length) {
    data.posts.forEach(function(post) {
      postResults += '<div class="result-item"><a href="/post/open/' + post.id + '">' + post.title + '</a></div>';
    });
  } else {
    postResults += '<p>No posts found.</p>';
  }
  postResults += '</div></div>';

  // Append users
  var userResults = '<div class="search-category"><h3>Users</h3><div class="results">';
  if (data.users.length) {
    data.users.forEach(function(user) {
      userResults += '<div class="result-item"><a href="/profile/' + user.username + '">' + user.name + '</a></div>';
    });
  } else {
    userResults += '<p>No users found.</p>';
  }
  userResults += '</div></div>';

  // Append comments
  var commentResults = '<div class="search-category"><h3>Comments</h3><div class="results">';
  if (data.comments.length) {
    data.comments.forEach(function(comment) {
      commentResults += '<div class="result-item"><a href="/post/open/' + comment.post_id + '">' + comment.text + '</a></div>';
    });
  } else {
    commentResults += '<p>No comments found.</p>';
  }
  commentResults += '</div></div>';

  // Append everything to the search-results div
  $(".search-results").append(postResults + userResults + commentResults);
  showCategory('posts');
}

function showCategory(categoryId) {
  $('.search-category').removeClass('active');
  $('#' + categoryId).addClass('active');
}
$(document).on('click', function(event) {
  if (!$(event.target).closest('.search-results, input[name="query"]').length) {
    hideSearchResults();
  }
});

function hideSearchResults() {
  $('.search-results').hide();
  $('.category-buttons').hide();
}



