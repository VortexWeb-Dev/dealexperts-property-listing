<div class="pagination mt-4">
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= max(1, $page - 1) ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php
                $range = 2;
                $showPages = [1, $totalPages];

                for ($i = max(2, $page - $range); $i <= min($totalPages - 1, $page + $range); $i++) {
                    $showPages[] = $i;
                }

                $showPages = array_unique(array_merge($showPages, range(1, min(3, $totalPages)), range(max(1, $totalPages - 2), $totalPages)));

                for ($i = 1; $i <= $totalPages; $i++):
                    if (in_array($i, $showPages)):
                ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php elseif ($i < $page && !in_array($i + 1, $showPages)): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php $i = $page - $range - 1;
                        ?>
                    <?php elseif ($i > $page && !in_array($i - 1, $showPages)): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php $i = $totalPages - 2;
                        ?>
                    <?php endif; ?>
                <?php endfor; ?>

                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>