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

  function showCreatePostForm() {
    let createPostForm = document.getElementById('create_post_form');
    createPostForm.style.display = 'block';

    let postCreatorButton = document.querySelector('#create_new_post button.create_new_post');
    postCreatorButton.innerText = 'Cancel';

    postCreatorButton.removeEventListener('click', showCreatePostForm);
    postCreatorButton.addEventListener('click', hideCreatePostForm);
  }

  function hideCreatePostForm() {
    let createPostForm = document.getElementById('create_post_form');
    createPostForm.style.display = 'none';
    
    let postCreatorButton = document.querySelector('#create_new_post button.create_new_post');
    postCreatorButton.textContent = 'Create New Post';

    postCreatorButton.removeEventListener('click', hideCreatePostForm);
    postCreatorButton.addEventListener('click', showCreatePostForm);
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
    var form = document.getElementById('create_new_post');
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}