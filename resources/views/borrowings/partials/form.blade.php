@php
    $selectedMemberId = old('member_id', $borrowing?->member_id);
    $selectedBookId = old('book_id', $borrowing?->book_id);

    $membersData = $members->map(fn ($member) => [
        'id' => $member->id,
        'label' => $member->member_code.' - '.$member->name,
    ])->values();

    $booksData = $books->map(fn ($book) => [
        'id' => $book->id,
        'title' => $book->title,
        'stock' => $book->stock,
        'rack' => $book->rack?->name ?? '-',
        'category' => $book->category?->name ?? '-',
    ])->values();

    $selectedMemberLabel = $borrowing?->member
        ? $borrowing->member->member_code.' - '.$borrowing->member->name
        : optional($membersData->firstWhere('id', (int) $selectedMemberId))['label'];

    $selectedBookTitle = optional($booksData->firstWhere('id', (int) $selectedBookId))['title'];
@endphp

<form class="form-card borrowing-form" method="POST" action="{{ $action }}" id="borrowing-form">
    @csrf
    @if (($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    <ol class="step-flow">
        {{-- Langkah 1: cari anggota --}}
        <li class="step-item @error('member_id') has-error @enderror">
            <div class="step-head">
                <span class="step-no">1</span>
                <div>
                    <strong>Cari Anggota</strong>
                    <p class="muted">Ketik nama atau nomor anggota, lalu pilih dari daftar.</p>
                </div>
            </div>

            <div class="combo" data-combo="member">
                <input type="hidden" name="member_id" id="member_id" value="{{ $selectedMemberId }}">
                <input type="text" class="combo-input" id="member_search" autocomplete="off"
                       placeholder="Cari anggota..." value="{{ $selectedMemberLabel }}">
                <ul class="combo-list" id="member_list" hidden></ul>
            </div>
            @error('member_id')<span class="field-error">{{ $message }}</span>@enderror
        </li>

        {{-- Langkah 2: cari buku --}}
        <li class="step-item is-locked @error('book_id') has-error @enderror" id="step-book">
            <div class="step-head">
                <span class="step-no">2</span>
                <div>
                    <strong>Cari Buku</strong>
                    <p class="muted">Aktif setelah anggota dipilih. Pilih buku untuk melihat rak, kategori, dan stok.</p>
                </div>
            </div>

            <div class="combo" data-combo="book">
                <input type="hidden" name="book_id" id="book_id" value="{{ $selectedBookId }}">
                <input type="text" class="combo-input" id="book_search" autocomplete="off"
                       placeholder="Cari judul buku..." value="{{ $selectedBookTitle }}" disabled>
                <ul class="combo-list" id="book_list" hidden></ul>
            </div>
            @error('book_id')<span class="field-error">{{ $message }}</span>@enderror

            <div class="book-info" id="book_info" hidden>
                <div><span class="muted">Nama Buku</span><strong id="info_title">-</strong></div>
                <div><span class="muted">Rak</span><strong id="info_rack">-</strong></div>
                <div><span class="muted">Kategori</span><strong id="info_category">-</strong></div>
                <div><span class="muted">Stok</span><strong id="info_stock">-</strong></div>
            </div>
        </li>

        {{-- Langkah 3: tanggal --}}
        <li class="step-item is-locked" id="step-dates">
            <div class="step-head">
                <span class="step-no">3</span>
                <div>
                    <strong>Tanggal Peminjaman</strong>
                    <p class="muted">Aktif setelah buku dipilih.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="field @error('borrowed_at') has-error @enderror">
                    <label for="borrowed_at">Tanggal Pinjam</label>
                    <input id="borrowed_at" type="date" name="borrowed_at"
                           value="{{ old('borrowed_at', $borrowing?->borrowed_at?->toDateString() ?? now()->toDateString()) }}" disabled>
                    @error('borrowed_at')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="field @error('due_date') has-error @enderror">
                    <label for="due_date">Tanggal Jatuh Tempo</label>
                    <input id="due_date" type="date" name="due_date"
                           value="{{ old('due_date', $borrowing?->due_date?->toDateString() ?? now()->addDays(7)->toDateString()) }}" disabled>
                    @error('due_date')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </li>
    </ol>

    <div class="actions" style="margin-top:18px;">
        <button class="btn" type="submit" id="submit_btn">{{ $submitLabel ?? 'Simpan' }}</button>
        <a class="btn secondary" href="{{ route('borrowings.index') }}">Batal</a>
    </div>
</form>

@push('scripts')
<script>
    (function () {
        const members = @json($membersData);
        const books = @json($booksData);

        function setupCombo(key, data, onSelect, getLabel) {
            const root = document.querySelector('[data-combo="' + key + '"]');
            const hidden = root.querySelector('input[type="hidden"]');
            const input = root.querySelector('.combo-input');
            const list = root.querySelector('.combo-list');

            function render(items) {
                list.innerHTML = '';
                if (items.length === 0) {
                    const li = document.createElement('li');
                    li.className = 'combo-empty';
                    li.textContent = 'Tidak ada hasil';
                    list.appendChild(li);
                } else {
                    items.slice(0, 50).forEach(function (item) {
                        const li = document.createElement('li');
                        li.textContent = getLabel(item);
                        li.addEventListener('mousedown', function (e) {
                            e.preventDefault();
                            hidden.value = item.id;
                            input.value = getLabel(item);
                            list.hidden = true;
                            onSelect(item);
                        });
                        list.appendChild(li);
                    });
                }
                list.hidden = false;
            }

            input.addEventListener('input', function () {
                hidden.value = '';
                onSelect(null);
                const q = input.value.toLowerCase().trim();
                const filtered = data.filter(function (item) {
                    return getLabel(item).toLowerCase().includes(q);
                });
                render(filtered);
            });

            input.addEventListener('focus', function () {
                if (input.disabled) return;
                const q = input.value.toLowerCase().trim();
                render(data.filter(function (item) { return getLabel(item).toLowerCase().includes(q); }));
            });

            document.addEventListener('click', function (e) {
                if (!root.contains(e.target)) list.hidden = true;
            });

            return { hidden: hidden, input: input };
        }

        const bookSearch = document.getElementById('book_search');
        const stepBook = document.getElementById('step-book');
        const stepDates = document.getElementById('step-dates');
        const borrowedAt = document.getElementById('borrowed_at');
        const dueDate = document.getElementById('due_date');
        const bookInfo = document.getElementById('book_info');

        function lockDates() {
            stepDates.classList.add('is-locked');
            borrowedAt.disabled = true;
            dueDate.disabled = true;
        }
        function unlockDates() {
            stepDates.classList.remove('is-locked');
            borrowedAt.disabled = false;
            dueDate.disabled = false;
        }
        function lockBook() {
            stepBook.classList.add('is-locked');
            bookSearch.disabled = true;
        }
        function unlockBook() {
            stepBook.classList.remove('is-locked');
            bookSearch.disabled = false;
        }
        function showBookInfo(book) {
            if (!book) { bookInfo.hidden = true; return; }
            document.getElementById('info_title').textContent = book.title;
            document.getElementById('info_rack').textContent = book.rack;
            document.getElementById('info_category').textContent = book.category;
            document.getElementById('info_stock').textContent = book.stock;
            bookInfo.hidden = false;
        }

        const bookCombo = setupCombo('book', books, function (book) {
            if (book) { showBookInfo(book); unlockDates(); }
            else { showBookInfo(null); lockDates(); }
        }, function (b) { return b.title; });

        setupCombo('member', members, function (member) {
            if (member) {
                unlockBook();
            } else {
                // reset langkah berikutnya bila anggota dihapus
                bookCombo.hidden.value = '';
                bookCombo.input.value = '';
                lockBook();
                showBookInfo(null);
                lockDates();
            }
        }, function (m) { return m.label; });

        // Inisialisasi state dari nilai lama (validasi gagal / mode edit)
        if (document.getElementById('member_id').value) {
            unlockBook();
        }
        const initialBookId = document.getElementById('book_id').value;
        if (initialBookId) {
            const book = books.find(function (b) { return String(b.id) === String(initialBookId); });
            if (book) { showBookInfo(book); unlockDates(); }
        }

        // Validasi urutan saat submit
        document.getElementById('borrowing-form').addEventListener('submit', function (e) {
            if (!document.getElementById('member_id').value) {
                e.preventDefault();
                alert('Silakan cari dan pilih anggota terlebih dahulu.');
                return;
            }
            if (!document.getElementById('book_id').value) {
                e.preventDefault();
                alert('Silakan cari dan pilih buku terlebih dahulu.');
                return;
            }
        });
    })();
</script>
@endpush
