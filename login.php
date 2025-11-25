<?php
/**
 * Página de Login e Cadastro - CineTrack
 */
require_once 'config/config.php';

// Se já estiver logado, redireciona
if (isLoggedIn()) {
    redirect('dashboard.php');
}

$pageTitle = "Login - CineTrack";

include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="row g-0">
                    <!-- Coluna Esquerda - Login -->
                    <div class="col-md-6 border-end">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="bi bi-box-arrow-in-right text-primary" style="font-size: 3rem;"></i>
                                <h2 class="fw-bold mt-3">Entrar</h2>
                                <p class="text-muted">Acesse sua conta</p>
                            </div>
                            
                            <form action="auth/login_process.php" method="POST" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="loginEmail" class="form-label">
                                        <i class="bi bi-envelope"></i> E-mail
                                    </label>
                                    <input type="email" 
                                           class="form-control form-control-lg" 
                                           id="loginEmail" 
                                           name="email" 
                                           required
                                           placeholder="seu@email.com">
                                    <div class="invalid-feedback">
                                        Por favor, insira um e-mail válido.
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="loginPassword" class="form-label">
                                        <i class="bi bi-lock"></i> Senha
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control form-control-lg" 
                                               id="loginPassword" 
                                               name="senha" 
                                               required
                                               placeholder="••••••••">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">
                                        Por favor, insira sua senha.
                                    </div>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                                    <label class="form-check-label" for="rememberMe">
                                        Lembrar de mim
                                    </label>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                    <i class="bi bi-box-arrow-in-right"></i> Entrar
                                </button>
                                
                                <div class="text-center">
                                    <small class="text-muted">
                                        Esqueceu a senha? <a href="#" class="text-decoration-none">Recuperar</a>
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Coluna Direita - Cadastro -->
                    <div class="col-md-6">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="bi bi-person-plus text-success" style="font-size: 3rem;"></i>
                                <h2 class="fw-bold mt-3">Cadastrar</h2>
                                <p class="text-muted">Crie sua conta grátis</p>
                            </div>
                            
                            <form action="auth/register_process.php" method="POST" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="registerName" class="form-label">
                                        <i class="bi bi-person"></i> Nome Completo
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           id="registerName" 
                                           name="nome" 
                                           required
                                           minlength="3"
                                           placeholder="Seu nome completo">
                                    <div class="invalid-feedback">
                                        Nome deve ter pelo menos 3 caracteres.
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="registerEmail" class="form-label">
                                        <i class="bi bi-envelope"></i> E-mail
                                    </label>
                                    <input type="email" 
                                           class="form-control form-control-lg" 
                                           id="registerEmail" 
                                           name="email" 
                                           required
                                           placeholder="seu@email.com">
                                    <div class="invalid-feedback">
                                        Por favor, insira um e-mail válido.
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="registerPassword" class="form-label">
                                        <i class="bi bi-lock"></i> Senha
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control form-control-lg" 
                                               id="registerPassword" 
                                               name="senha" 
                                               required
                                               minlength="6"
                                               placeholder="Mínimo 6 caracteres">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">
                                        Senha deve ter pelo menos 6 caracteres.
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="registerPasswordConfirm" class="form-label">
                                        <i class="bi bi-lock-fill"></i> Confirmar Senha
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control form-control-lg" 
                                               id="registerPasswordConfirm" 
                                               name="senha_confirma" 
                                               required
                                               minlength="6"
                                               placeholder="Confirme sua senha">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">
                                        As senhas devem ser iguais.
                                    </div>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="acceptTerms" required>
                                    <label class="form-check-label" for="acceptTerms">
                                        Aceito os <a href="#" class="text-decoration-none">termos de uso</a>
                                    </label>
                                    <div class="invalid-feedback">
                                        Você deve aceitar os termos de uso.
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="bi bi-person-plus"></i> Criar Conta
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informações Adicionais -->
            <div class="text-center mt-4">
                <p class="text-muted">
                    <i class="bi bi-shield-check"></i> Seus dados estão seguros conosco
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Script para validar senhas iguais -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('form[action="auth/register_process.php"]');
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const senha = document.getElementById('registerPassword').value;
            const senhaConfirma = document.getElementById('registerPasswordConfirm').value;
            
            if (senha !== senhaConfirma) {
                e.preventDefault();
                e.stopPropagation();
                
                const confirmInput = document.getElementById('registerPasswordConfirm');
                confirmInput.setCustomValidity('As senhas não coincidem');
                confirmInput.classList.add('is-invalid');
                
                // Adiciona mensagem de erro customizada
                const feedback = confirmInput.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = 'As senhas não coincidem!';
                }
            } else {
                document.getElementById('registerPasswordConfirm').setCustomValidity('');
            }
        });
        
        // Remove erro ao digitar
        document.getElementById('registerPasswordConfirm').addEventListener('input', function() {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>