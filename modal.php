<div class="modal fade" id="solicitacaoModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel<?php echo $row['id']; ?>">Detalhes do Chamado #<?php echo $row['id']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Cliente:</strong> <?php echo $row['nomeCliente']; ?></p>
                <p><strong>Item:</strong> <?php echo $row['itemSolicitado']; ?></p>
                <p><strong>Especificação:</strong> <?php echo $row['especificacaoTecnica']; ?></p>
                <p><strong>Quantidade:</strong> <?php echo $row['quantidade']; ?></p>
                <p><strong>Data:</strong> <?php echo $row['dataSolicitacao']; ?></p>
                <p><strong>Solicitante:</strong> <?php echo $row['nomeSolicitante']; ?></p>
                <p><strong>Onde será Utilizado:</strong> <?php echo $row['ondeSeraUtilizado']; ?></p>
                <p><strong>Prioridade:</strong> <?php echo $row['prioridade']; ?></p>
                <form method="POST" action="alterar_status.php">
                    <div class="mb-3">
                        <label for="novo_status">Status:</label>
                        <select class="form-select novo_status" name="novo_status">
                            <option value="Aprovado">Aprovado</option>
                            <option value="Pendente">Pendente</option>
                            <option value="Recusado">Recusado</option>
                            <option value="Novo">Novo</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="novo_estagio">Estágio:</label>
                        <select class="form-select novo_estagio" name="novo_estagio">
                            <!-- Opções de estágio aqui -->
                        </select>
                    </div>

                    <input type="hidden" name="solicitacao_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-primary">Alterar Status</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
