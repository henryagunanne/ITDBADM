<section class="testimonials-container">
    <h2>Hear From Our Customers</h2>
    <div class="testimonials">
        <?php foreach ($testimonials as $t): ?>
            <div class="testimony">
                <h4><?= htmlspecialchars($t['title']) ?></h4>
                <p><?= htmlspecialchars($t['description']) ?></p>
                <h5>- <?= htmlspecialchars($t['author']) ?></h5>
            </div>
        <?php endforeach; ?>
    </div>
</section>