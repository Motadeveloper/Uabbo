   document.addEventListener("DOMContentLoaded", function() {
    let page = 1;
    const perPage = 5;
    let loading = false;

    function loadSorteios() {
        if (loading) return;
        loading = true;

        document.getElementById("loadingSpinner").style.display = "block";

        fetch(`/api/sorteios?page=${page}&perPage=${perPage}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("loadingSpinner").style.display = "none";
                loading = false;

                const sorteiosEmAndamento = data.sorteiosEmAndamento || [];
                const sorteiosRealizados = data.sorteiosRealizados || [];

                if (sorteiosEmAndamento.length === 0 && page === 1) {
                    document.getElementById("noSorteiosMessage").style.display = "block";
                }

                // Carregar sorteios em andamento
                sorteiosEmAndamento.forEach(sorteio => {
                    const participantesHTML = sorteio.participantes?.length ? 
                        `<div class="carousel-container w-100">
                            <div class="carousel">
                                ${sorteio.participantes.map(participante => `
                                    <div class="mini-card text-center">
                                        <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=${participante.name}&size=m&head_direction=3&direction=2&headonly=1&img_format=png&gesture=sml&action=std"
                                             alt="Avatar de ${participante.name}" class="rounded-circle" style="width: 54px; height: 54px;">
                                        <p class="participant-name">${participante.name.toUpperCase()}</p>
                                    </div>
                                `).join('')}
                            </div>
                        </div>`
                        : `<p class="text-muted">Nenhum participante</p>`;

                    const sorteioCard = `
                        <div class="card mb-3 card-sorteio animate__animated animate__fadeIn">
                            <div class="card-body">
                                <!-- Linha do Avatar + Título/Descrição + Botão -->
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center">
                                        <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=${sorteio.author_name}&action=std&direction=2&head_direction=1&gesture=sml&size=b" 
                                             alt="Avatar de ${sorteio.author_name}" 
                                             class="avatar-img rounded-circle me-3" style="width: 64px; height: 110px;">
                                        <div>
                                            <h5 class="card-title mb-1">${sorteio.title}</h5>
                                            <p class="card-text">${sorteio.description}</p>
                                            <p class="author-name text-muted">${sorteio.author_name}</p>
                                        </div>
                                    </div>
                                    <a href="/sorteio/${sorteio.id}" class="btn btn-outline-primary">
    <img src="https://www.habboassets.com/assets/images/catalog/icons/icon_297.png" alt="Ícone" class="ver-sorteio-icon">
    Ver Sorteio
</a>

                                </div>

                                <!-- Premiações ocupando 100% da largura -->
                                <div class="premios-wrapper w-100 mt-3">
                                    <h6 class="premios-title">Premiações</h6>
                                    <ul class="list-group premios-list w-100">
                                        ${sorteio.premios ? sorteio.premios.slice(0, 3).map(premio => `
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    ${premio.posicao == 1 ? '<img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi4n2kXJJ2VYkyEL2Q8Tbvj6XIQ5EU63AMa8grgo9QFQF3iHKX5nOJk7g1rU-ALr9e2f7Y_qELl1Ap5cgKPV8ogVKkN9UcjkIbX9DNLO_Zp9nmmQwQvARSMO1eOeRsBm0pKCswx-2rE9uuQ/s0/fx_icon_111.png" alt="Primeiro Lugar" class="me-2" style="width: 29px; height: 36px;">' : ''}
                                                    ${premio.posicao == 2 ? '<img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhCxxpV9DnzzI9SDDaLxmpsVU_DgO5sZA8hpB2lKrS5ocPSijOrEjNAJJTnCFhxLGjWe8ccqjjHYoxQaFDlAanhGwrQUU7qm12PpS5tAyXrXxsOC3dmvkPyUkgZqFNVBDR0Qk4fWdmNxRJE/s0/fx_icon_110.png" alt="Segundo Lugar" class="me-2" style="width: 29px; height: 36px;">' : ''}
                                                    ${premio.posicao == 3 ? '<img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhJXk1Hw7BxtPsY3I2I6PBvfBM2kkKLNn16bm6OhsmsZNXehw-GPFHRg4HFcv154BWsy57RiaK1xnEblAmKMy6Mvh8hslV0ubko4GtJDQB2J9Y_WhiycLw9atcklSXlRtXMnFk_uGKgsmbg/s0/fx_icon_109.png" alt="Terceiro Lugar" class="me-2" style="width: 29px; height: 36px;">' : ''}
                                                    <strong>${premio.posicao}º Lugar</strong>
                                                </div>
                                                <span class="d-flex align-items-center">
                                                    ${premio.premio_quantidade} 
                                                    ${premio.premio_tipo === 'hc' ? '<span class="ms-2">HC</span><img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEitSGjGiPXmdduJZ331HTVpc6SWIgWo_OXbA9yNthxOp9upLFKZxG5rSRYn13kWtbjk3cnjOvutCeRfpgd3drUZZXyOU1zXz8yqbIhvba0BDU7pm6wla0azo-OoMvoovQRqNDANS1XCpL2y/s0/hc_icon_small.png" class="ms-2" style="width: 28px; height: 28px;">' : ''}
                                                    ${premio.premio_tipo === 'cambios' ? '<span class="ms-2">Câmbios</span><img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEj9j5dWGNIm5OBBMc_5tSPMgHiYIYpBzR-SyE0lgaR3ioPVSqT-jSaf0a9tpPn-xOm0W4DZ8VS0S9ruxDNqwKLqsrjrauo7EXzHVuQe0ycbxAfmE6bSc13zLZ73wByciI6Fd7hsyd-5CKFL/s0/money_small_coin.png" class="ms-2" style="width: 22px; height: 22px;">' : ''}
                                                </span>
                                            </li>
                                        `).join('') : '<p class="text-muted">Nenhuma premiação disponível</p>'}
                                    </ul>
                                </div>

                                <!-- Participantes ocupando 100% da largura -->
                                <div class="participantes-wrapper w-100 mt-3">
                                    <h5>${sorteio.participantes?.length || 0} Participantes </h5>
                                    ${participantesHTML}
                                </div>
                            </div>
                        </div>
                    `;
                    document.getElementById("sorteiosEmAndamento").insertAdjacentHTML("afterbegin", sorteioCard);
                });

                if (sorteiosRealizados.length) {
                    sorteiosRealizados.forEach(sorteio => {
                        const ganhadoresHTML = sorteio.premio_detalhes?.length
                            ? sorteio.premio_detalhes.map(vencedor => `
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=${vencedor.user_name}&size=m&head_direction=1&direction=1&headonly=0&img_format=png&gesture=sml&action=std" 
                                             alt="${vencedor.user_name}" class="rounded-circle me-3" style="width: 64px; height: 110px;">
                                        <div>
                                            <p><strong>${vencedor.posicao}º Lugar:</strong> ${vencedor.user_name}</p>
                                            <p><strong>Prêmio:</strong> ${vencedor.premio_quantidade} 
                                                ${vencedor.premio_tipo === 'cambios' ? 'Câmbios' : 'HC'}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            `).join('')
                            : `<p class="text-muted">Nenhum vencedor disponível</p>`;
                
                        const sorteioCard = `
                            <div class="card mb-3 card-sorteio animate__animated animate__fadeIn" style="background: linear-gradient(to bottom, #f5f0f8 50%, #f0e8f4 50%); border: 2px solid #faf7fb;">
                                <div class="card-header d-flex justify-content-between align-items-center" style="border-bottom: none;">
                                    <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjnLTR5NMsnyvyiKwRj-kLaV86LHqEZ9FhmDzF61D2Rkbzv09VU0QspOmRSisrYh47xQXl9KYPMfQhKVbvZ15owF7zV8uae8Mtw-aVUBhZei7fQXtUbDlXAn-exwf-t3knvLyP6x0A5naZA/s0/tick.png" 
                     alt="Ícone" class="icon-left me-2">
                <h5 class="card-title m-0">${sorteio.title}</h5>
                                    <a href="/sorteio/${sorteio.id}" class="btn btn-outline-primary"><img src="https://www.habboassets.com/assets/images/catalog/icons/icon_297.png" alt="Ícone" class="ver-sorteio-icon">Ver Sorteio</a>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">${sorteio.description}</p>
                                    <h6>Vencedores</h6>
                                    <div class="vencedores-section">${ganhadoresHTML}</div>
                                </div>
                            </div>
                        `;
                        
                        document.querySelector('#sorteiosRealizados').insertAdjacentHTML('afterbegin', sorteioCard);
                    });
                } else {
                    document.querySelector('#noSorteiosMessage').style.display = 'block';
                }                

                if (sorteiosEmAndamento.length < perPage && sorteiosRealizados.length < perPage) {
                    window.removeEventListener("scroll", handleScroll);
                }

                page++;
            })
            .catch(error => {
                console.error("Erro ao carregar sorteios:", error);
                loading = false;
                document.getElementById("loadingSpinner").style.display = "none";
            });
    }

    function handleScroll() {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100 && !loading) {
            loadSorteios();
        }
    }

    window.addEventListener("scroll", handleScroll);
    loadSorteios();
});





