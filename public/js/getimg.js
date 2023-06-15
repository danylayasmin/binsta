var images = document.querySelectorAll('.post-image');

    images.forEach(function(image) {
        var postId = image.getAttribute('postId');
        var url = '/test/getImage?id=' + postId;

        fetch(url)
            .then(response => response.text())
            .then(data => {
                image.src = 'data:image/png;base64,' + data;
            });
    });