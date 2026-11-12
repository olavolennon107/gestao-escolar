document.addEventListener("DOMContentLoaded", function() {
    // Interatividade do Botão de Notificações
    const btnNotificacao = document.querySelector('.notification-btn');
    if (btnNotificacao) {
        btnNotificacao.addEventListener('click', function() {
            alert('Você tem 3 novas notificações do sistema.');
        });
    }
});

// Função para confirmar exclusão no banco de dados
function confirmarExclusao(item) {
    return confirm(`Tem certeza que deseja excluir este ${item}? Esta ação não poderá ser desfeita e removerá os dados do banco.`);
}