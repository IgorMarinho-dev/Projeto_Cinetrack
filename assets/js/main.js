/**
 * CineTrack - JavaScript Principal
 * Sistema de Catálogo de Filmes e Séries
 */

// Espera o DOM carregar
document.addEventListener('DOMContentLoaded', function() {
    
    // Auto-hide alerts após 5 segundos
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Animação de fade-in nos cards
    const cards = document.querySelectorAll('.movie-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 50);
    });
    
    // Confirmação de exclusão
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Tem certeza que deseja excluir?')) {
                e.preventDefault();
            }
        });
    });
    
    // Busca em tempo real
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let timeout = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const query = this.value.trim();
                if (query.length >= 2) {
                    performSearch(query);
                } else if (query.length === 0) {
                    location.reload();
                }
            }, 500);
        });
    }
    
    // Filtro de tipo (filme/série)
    const typeFilter = document.getElementById('typeFilter');
    if (typeFilter) {
        typeFilter.addEventListener('change', function() {
            const selectedType = this.value;
            filterByType(selectedType);
        });
    }
    
    // Preview de imagem no upload
    const posterInput = document.getElementById('poster');
    if (posterInput) {
        posterInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('posterPreview');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Toggle de senha (mostrar/ocultar)
    const togglePassword = document.querySelectorAll('.toggle-password');
    togglePassword.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
    
    // Validação de formulários
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // Rating stars (avaliação)
    const ratingStars = document.querySelectorAll('.rating-star');
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            document.getElementById('ratingInput').value = rating;
            
            // Atualiza visual das estrelas
            ratingStars.forEach(s => {
                if (s.dataset.rating <= rating) {
                    s.classList.add('text-warning');
                    s.classList.remove('text-muted');
                } else {
                    s.classList.remove('text-warning');
                    s.classList.add('text-muted');
                }
            });
        });
    });
    
    // Smooth scroll
    const scrollLinks = document.querySelectorAll('a[href^="#"]');
    scrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId !== '#') {
                e.preventDefault();
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });
});

/**
 * Função de busca em tempo real
 */
function performSearch(query) {
    const cards = document.querySelectorAll('.movie-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const title = card.querySelector('.movie-card-title').textContent.toLowerCase();
        const director = card.querySelector('[data-director]')?.textContent.toLowerCase() || '';
        const genre = card.querySelector('[data-genre]')?.textContent.toLowerCase() || '';
        
        const searchTerm = query.toLowerCase();
        
        if (title.includes(searchTerm) || director.includes(searchTerm) || genre.includes(searchTerm)) {
            card.parentElement.style.display = '';
            visibleCount++;
        } else {
            card.parentElement.style.display = 'none';
        }
    });
    
    // Mostra mensagem se nada encontrado
    const emptyState = document.getElementById('emptyState');
    if (emptyState) {
        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

/**
 * Filtra por tipo (filme/série)
 */
function filterByType(type) {
    const cards = document.querySelectorAll('.movie-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const cardType = card.querySelector('[data-type]')?.dataset.type;
        
        if (type === '' || cardType === type) {
            card.parentElement.style.display = '';
            visibleCount++;
        } else {
            card.parentElement.style.display = 'none';
        }
    });
    
    // Mostra mensagem se nada encontrado
    const emptyState = document.getElementById('emptyState');
    if (emptyState) {
        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

/**
 * Adiciona filme ao catálogo via AJAX
 */
function addToCatalog(filmeId, status) {
    const formData = new FormData();
    formData.append('filme_id', filmeId);
    formData.append('status', status);
    
    fetch('actions/adicionar_filme.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            // Atualiza a interface
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Erro ao adicionar filme');
        console.error('Error:', error);
    });
}

/**
 * Remove filme do catálogo
 */
function removeFromCatalog(catalogoId) {
    if (!confirm('Tem certeza que deseja remover este filme do seu catálogo?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('catalogo_id', catalogoId);
    
    fetch('actions/remover_filme.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Erro ao remover filme');
        console.error('Error:', error);
    });
}

/**
 * Atualiza status do filme
 */
function updateStatus(catalogoId, newStatus) {
    const formData = new FormData();
    formData.append('catalogo_id', catalogoId);
    formData.append('status', newStatus);
    
    fetch('actions/atualizar_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Erro ao atualizar status');
        console.error('Error:', error);
    });
}

/**
 * Mostra alerta dinâmico
 */
function showAlert(type, message) {
    const alertTypes = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    };
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertTypes[type]} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alertDiv);
        bsAlert.close();
    }, 4000);
}

/**
 * Carrega mais filmes (lazy loading/paginação)
 */
function loadMore(page) {
    // Implementar se necessário
    console.log('Loading page:', page);
}

/**
 * Exporta dados do catálogo (opcional)
 */
function exportCatalog() {
    window.location.href = 'export.php';
}