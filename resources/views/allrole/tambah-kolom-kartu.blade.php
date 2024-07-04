<script>
    function addColumnScript(event) {
        event.preventDefault();
        let form = document.getElementById('addColForm');
        let formData = new FormData(form);
        let url = form.getAttribute('action');

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.id) {
                let newColumn = document.createElement('div');
                newColumn.classList.add('kolom-card');
                newColumn.id = `kolom-card-${data.id}`;
                newColumn.setAttribute('onmouseenter', 'aksiKolomShow(' + data.id + ')');
                newColumn.setAttribute('onmouseleave', 'aksiKolomHide(' + data.id + ')');
                newColumn.dataset.id = data.id;

                // Container kolom ketika ditambahkan
                newColumn.innerHTML = `
                    <div class="dropdown dropdown-action aksi-kolom" id="aksi-kolom${data.id}">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item" onclick="updateColumnModal(${data.id}, '${data.name}', '${data.updateUrl}');" id="edit-column-${data.id}">
                                <i class="fa fa-pencil m-r-5"></i> Edit
                            </a>
                            <a href="#" class="dropdown-item" onclick="deleteColumnModal(${data.id}, '${data.name}', '${data.deleteUrl}');">
                                <i class='fa fa-trash-o m-r-5'></i> Delete
                            </a>
                        </div>
                    </div>
                    <h5 id="kolomNama${data.id}" class="kolom-nama mb-3 font-semibold text-lgs dark:text-white">${data.name}</h5>
                    <ul class="card-container" id="containerCard${data.id}">
                    </ul>
                    <div class="card-loghub hidden" id="cardLoghub${data.id}">
                        <div class="flex items-center p-3 text-base font-bold rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                            <form id="addCardForm${data.id}" action="${data.addCardUrl}" method="POST" onsubmit="addCardScript(event, '${data.id}')">
                                @csrf
                                <input type="hidden" class="form-control" name="board_id" value="${data.board_id}">
                                <input type="hidden" class="form-control" name="team_id" value="${data.team_id}">
                                <input type="hidden" class="form-control" name="column_id" value="${data.id}">
                                <input type="text" class="form-control" name="name" id="cardName" style="width: 130%; border-radius: 10px; background-color: #f5fffa;" placeholder="Enter card's name..." required>
                                <button type="submit" class="btn btn-outline-info btn-add">Add card</button>
                            </form>
                        </div>
                    </div>
                    <button onclick="openAdd('${data.id}')" class="btn btn-outline-info" id="btn-add${data.id}">
                        <i class="fa-solid fa-plus"></i> Add a card...
                    </button>
                `;
                
                // Tambahkan kolom baru
                let cardContainer = document.getElementById('cardContainer');
                
                if (cardContainer) {
                    cardContainer.appendChild(newColumn);
                    toastr.success('Berhasil membuat kolom!');

                    // Menutup modal
                    $('#addCol').modal('hide');

                    // Memuat Ulang Form Kosongan
                    form.reset();
                    
                } else {
                    toastr.error('Gagal menemukan kontainer kolom!');
                }

            } else {
                toastr.error('Gagal membuat kolom, silahkan coba lagi!');
            }
        })

        .catch(error => {
            console.error('Kesalahan:', error);
            toastr.error('Gagal membuat kolom, silahkan coba lagi!');
        });
    }

    function addCardScript(event, columnId) {
        event.preventDefault();
        let form = document.getElementById('addCardForm' + columnId);
        let formData = new FormData(form);
        let url = form.getAttribute('action');

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let cardContainer = document.getElementById('containerCard' + columnId);
                let newCard = document.createElement('li');
                newCard.classList.add('kartu-loghub');
                newCard.setAttribute('data-id', data.card.id);
                newCard.setAttribute('onmouseenter', 'aksiKartuShow(' + data.card.id + ')');
                newCard.setAttribute('onmouseleave', 'aksiKartuHide(' + data.card.id + ')');
                newCard.style.position = 'relative';

                // Container Kartu ketika ditambahkan
                newCard.innerHTML = `
                    <!-- Tampilan Aksi Edit -->
                    <div class="cover-card card-cover2-${data.card.pattern} ${data.card.pattern ? '' : 'hiddens'}" id="cover-card-${data.card.id}"></div>
                    <div class="dropdown dropdown-action aksi-card" id="aksi-card${data.card.id}" style="position: absolute !important;">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-pencil fa-sm aksi-card-icon"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item" onclick="updateCardModal(${data.card.id}, '${data.card.name}', '${data.card.updateUrl}');" id="edit-card-${data.card.id}">
                                <i class="fa-regular fa-pen-to-square m-r-5"></i> Edit
                            </a>
                            <a href="#" class="dropdown-item" onclick="deleteCardModal2('${data.card.id}', '${data.card.name}', '${data.column.name}', '${data.card.deleteUrl}');">
                                <i class='fa fa-trash-o m-r-5'></i> Delete
                            </a>
                        </div>
                    </div>
                    <!-- /Tampilan Aksi Edit -->

                    <!-- Tampilan Kartu Pengguna -->
                    <a href="#" data-toggle="modal" data-target="#isianKartu" onclick="$('#card_id').val(${data.card.id}); $('#form_kartu').submit();">
                        <div class="card-nama" ${data.card.pattern ? 'style="border-top-right-radius: 0 !important; border-bottom-right-radius: 8px !important; border-top-left-radius: 0 !important; border-bottom-left-radius: 8px !important;"' : ''}>
                            <span class="flex ms-3" id="span-nama-${data.card.id}" style="width: 150px; ${data.card.description ? 'margin-bottom: 10px;' : ''}">${data.card.name}</span>
                            <div class="tampilan-info gap-2">

                                <!-- Muncul apabila terdapat deskripsi pada kartu -->
                                ${data.card.description ? `
                                    <div class="info-status8" id="descriptionStatus${data.card.id}">
                                        <i class="fa-solid fa-align-left icon-deskripsi-light
                                            @foreach($result_tema as $sql_mode => $mode_tema)
                                                @if($mode_tema->tema_aplikasi == 'Gelap')
                                                    icon-deskripsi-dark
                                                @endif
                                            @endforeach">
                                        </i>
                                        <span class="text-status8"><b>This card has a description.</b></span>
                                    </div>` : `
                                    <div class="info-status8 hidden" id="descriptionStatus${data.card.id}">
                                        <i class="fa-solid fa-align-left icon-deskripsi-light
                                            @foreach($result_tema as $sql_mode => $mode_tema)
                                                @if($mode_tema->tema_aplikasi == 'Gelap')
                                                    icon-deskripsi-dark
                                                @endif
                                            @endforeach">
                                        </i>
                                        <span class="text-status8"><b>This card has a description.</b></span>
                                    </div>`}
                                <!-- /Muncul apabila terdapat deskripsi pada kartu -->

                            </div>
                        </div>
                    </a>
                    <!-- /Tampilan Kartu Pengguna -->
                `;
                cardContainer.appendChild(newCard);
                toastr.success('Berhasil membuat kartu!');

                // Memuat Ulang Form Kosongan
                form.reset();

                // Fitur Buka dan Tutup Tambah Kartu
                const cardLoghub = document.getElementById('cardLoghub' + columnId);
                const btnadd = document.getElementById('btn-add' + columnId);
                let style = cardLoghub.getAttribute("class");
                if (style.includes('flex')) {
                    cardLoghub.classList.remove("flex");
                    btnadd.innerHTML = "<i class='fa-solid fa-plus'></i> Add a card...";
                } else {
                    cardLoghub.classList.add("flex");
                    btnadd.innerHTML = "Cancel";
                }
                // End Fitur Buka dan Tutup Tambah Kartu

            } else {
                toastr.error('Gagal menambahkan kartu!');
            }
        })
        .catch(error => {
            console.error('Kesalahan:', error);
        });
    }
</script>