document.addEventListener('DOMContentLoaded', function() {
    const handleLikeAction = async (commentId, action) => {
        try {
            const response = await fetch(`/comment/${commentId}/${action}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            updateLikeButtons(commentId, data);
        } catch (error) {
            console.error('Error:', error);
        }
    };

    const updateLikeButtons = (commentId, data) => {
        const likeBtn = document.querySelector(`#like-btn-${commentId}`);
        const dislikeBtn = document.querySelector(`#dislike-btn-${commentId}`);
        const likeCount = document.querySelector(`#like-count-${commentId}`);
        const dislikeCount = document.querySelector(`#dislike-count-${commentId}`);

        if (likeBtn && dislikeBtn) {
            likeBtn.classList.toggle('active', data.isLiked);
            dislikeBtn.classList.toggle('active', data.isDisliked);
        }

        if (likeCount && dislikeCount) {
            likeCount.textContent = data.likes;
            dislikeCount.textContent = data.dislikes;
        }
    };

    // Gestionnaire pour les likes
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            handleLikeAction(commentId, 'like');
        });
    });

    // Gestionnaire pour les dislikes
    document.querySelectorAll('.dislike-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            handleLikeAction(commentId, 'dislike');
        });
    });

    // Gestionnaire pour la suppression des commentaires (admin)
    document.querySelectorAll('.delete-comment-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
                const commentId = this.dataset.commentId;
                handleDeleteComment(commentId, this);
            }
        });
    });
});

function handleDeleteComment(commentId, buttonElement) {
    fetch(`/comment/${commentId}/delete`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Trouver et supprimer le commentaire du DOM
            const commentElement = buttonElement.closest('.comment');
            if (commentElement) {
                commentElement.remove();
            }
        } else {
            alert(data.error || 'Une erreur est survenue lors de la suppression');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la suppression');
    });
}
