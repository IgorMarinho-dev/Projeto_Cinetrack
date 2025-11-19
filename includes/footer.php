</main>

<!-- Footer -->
<footer class="bg-dark text-white mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5><i class="bi bi-film"></i> <?php echo SITE_NAME; ?></h5>
                <p class="text-muted">Seu catálogo pessoal de filmes e séries.</p>
            </div>
            
            <div class="col-md-4">
                <h5>Links Rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo SITE_URL; ?>/index.php" class="text-decoration-none text-muted">Início</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/catalogo.php" class="text-decoration-none text-muted">Catálogo</a></li>
                    <?php if (isLoggedIn()): ?>
                    <li><a href="<?php echo SITE_URL; ?>/meu-catalogo.php" class="text-decoration-none text-muted">Meu Catálogo</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="col-md-4">
                <h5>Estatísticas</h5>
                <?php
                try {
                    $db = getDB();
                    $stats = $db->query("
                        SELECT 
                            (SELECT COUNT(*) FROM filmes_series) as total_titulos,
                            (SELECT COUNT(*) FROM usuarios WHERE tipo = 'usuario') as total_usuarios,
                            (SELECT COUNT(*) FROM catalogo_usuario) as total_avaliacoes
                    ")->fetch();
                    ?>
                    <ul class="list-unstyled text-muted">
                        <li><i class="bi bi-film"></i> <?php echo number_format($stats['total_titulos']); ?> títulos</li>
                        <li><i class="bi bi-people"></i> <?php echo number_format($stats['total_usuarios']); ?> usuários</li>
                        <li><i class="bi bi-star"></i> <?php echo number_format($stats['total_avaliacoes']); ?> avaliações</li>
                    </ul>
                <?php } catch (Exception $e) { ?>
                    <p class="text-muted">Sistema em manutenção</p>
                <?php } ?>
            </div>
        </div>
        
        <hr class="border-secondary">
        
        <div class="row">
            <div class="col-12 text-center text-muted">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Desenvolvido para Programação Web.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript Customizado -->
<script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>

</body>
</html>