document.addEventListener('DOMContentLoaded', function () {
    var likeButtons = document.querySelectorAll('.like-button');

    likeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var postId = button.getAttribute('data-post-id');
            likePost(postId);
        });
    });

    function likePost(postId) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/test/like', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Like succcessful
                    console.log('Post liked successfully');
                } else {
                    // Like failed
                    console.error('Failed to like post');
                }
            }
        };
        xhr.send('postId=' + encodeURIComponent(postId));
    }
});