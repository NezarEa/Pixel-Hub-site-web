$(document).ready(function () {
    // Toggle navigation menu
    $('.menu-icon').click(function () {
        $('.nav-links').toggleClass('active');
    });

    // Toggle post form visibility
    $('#editPostBtn').click(function () {
        $('#postForm').toggle();
    });

    // Close post form
    $('.close').click(function () {
        $('#postForm').hide();
    });

    // Edit button click handler
    $(".edit-btn").click(function () {
        var id = $(this).data("id");
        var url = $(this).data("photo-id") ? "edit_photo.php" : "edit_article.php";
        $.get(url, { id: id }, function (data) {
            $("#editModal .modal-content").html(data);
            $('#editModal').modal('show');
        });
    });

    // Delete button click handler
    $(".delete-btn").click(function () {
        var id = $(this).data("id");
        var url = $(this).data("photo-id") ? "delete_photo.php" : "delete_article.php";
        $.get(url, { id: id }, function (data) {
            $("#deleteModal .modal-content").html(data);
            $('#deleteModal').modal('show');
        });
    });

    // Toggle post form visibility
    $('#editPostBtn').click(function () {
        $('#postForm').css('display', 'block');
    });

    $('.close').click(function () {
        $('#postForm').css('display', 'none');
    });
});

// Toggle between blog and post content
function toggleCheckbox(type) {
    const blogContent = document.getElementById('blogContent');
    const photoContent = document.getElementById('photoContent');
    if (type === 'blog') {
        blogContent.style.display = 'block';
        photoContent.style.display = 'none';
    } else if (type === 'post') {
        blogContent.style.display = 'none';
        photoContent.style.display = 'block';
    }
}

const swiper = new Swiper('.swiper', {
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    loop: true,
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});
