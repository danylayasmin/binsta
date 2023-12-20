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
        var button = document.querySelector('.like-button[data-post-id="' + postId + '"]');
        var likeCount = document.querySelector('.like-count[data-post-id="' + postId + '"]');
        xhr.open('POST', '/home/like', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Post liked successfully');

                    var action = JSON.parse(xhr.response).action;

                    if (action === 'liked') {
                        button.innerHTML = '<ion-icon name="heart" class="w-6 h-6 text-red-500"></ion-icon>';
                        likeCount.innerHTML = parseInt(likeCount.innerHTML) + 1;
                    } else {
                        button.innerHTML = '<ion-icon name="heart-outline" class="w-6 h-6"></ion-icon>';
                        likeCount.innerHTML = parseInt(likeCount.innerHTML) - 1;
                    }
                } else {
                    // Like failed
                    console.error('Failed to like post');
                }
            }
        };
        xhr.send('postId=' + encodeURIComponent(postId));
    }
});