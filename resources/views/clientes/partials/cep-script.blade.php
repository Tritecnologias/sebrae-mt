<script>
(function () {
    const cepInput    = document.getElementById('cep');
    const btnBuscar   = document.getElementById('btn-buscar-cep');
    const cepFeedback = document.getElementById('cep-feedback');

    function formatarCep(valor) {
        return valor.replace(/\D/g, '').replace(/^(\d{5})(\d)/, '$1-$2').substring(0, 9);
    }

    function preencherEndereco(dados) {
        document.getElementById('rua').value    = dados.rua    || '';
        document.getElementById('bairro').value = dados.bairro || '';
        document.getElementById('cidade').value = dados.cidade || '';
        document.getElementById('estado').value = dados.estado || '';
    }

    function limparEndereco() {
        ['rua', 'bairro', 'cidade', 'estado'].forEach(id => {
            document.getElementById(id).value = '';
        });
    }

    async function buscarCep(cep) {
        const soDigitos = cep.replace(/\D/g, '');
        if (soDigitos.length !== 8) return;

        btnBuscar.disabled = true;
        cepFeedback.classList.add('d-none');

        try {
            const response = await fetch(`/cep/${soDigitos}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const dados = await response.json();

            if (!response.ok) {
                cepFeedback.textContent = dados.error || 'CEP não encontrado.';
                cepFeedback.classList.remove('d-none');
                limparEndereco();
                return;
            }

            preencherEndereco(dados);
        } catch (e) {
            cepFeedback.textContent = 'Erro ao consultar o CEP. Tente novamente.';
            cepFeedback.classList.remove('d-none');
        } finally {
            btnBuscar.disabled = false;
        }
    }

    // Formata enquanto digita
    cepInput.addEventListener('input', function () {
        this.value = formatarCep(this.value);
    });

    // Busca ao sair do campo (blur)
    cepInput.addEventListener('blur', function () {
        buscarCep(this.value);
    });

    // Busca ao clicar no botão
    btnBuscar.addEventListener('click', function () {
        buscarCep(cepInput.value);
    });
})();
</script>
