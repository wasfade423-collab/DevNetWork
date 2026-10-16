document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('#developer-search');
    const cards = [...document.querySelectorAll('[data-developer-card]')];
    const emptySearchState = document.querySelector('#search-empty');
    const resultCount = document.querySelector('#result-count');

    if (searchInput && cards.length) {
        const filterCards = () => {
            const query = searchInput.value.trim().toLocaleLowerCase('fr');
            let visibleCount = 0;

            cards.forEach((card) => {
                const matches = card.dataset.search.includes(query);
                card.hidden = !matches;
                if (matches) visibleCount += 1;
            });

            if (emptySearchState) emptySearchState.hidden = visibleCount > 0;
            if (resultCount) resultCount.textContent = `${visibleCount} profil${visibleCount === 1 ? '' : 's'}`;
        };

        searchInput.addEventListener('input', filterCards);
        document.addEventListener('keydown', (event) => {
            if (event.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                event.preventDefault();
                searchInput.focus();
            }
        });
    }

    document.querySelectorAll('[data-add-option]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.input);
            const container = document.getElementById(button.dataset.container);
            const optionType = button.dataset.addOption;
            const value = input?.value.trim();

            if (!input || !container || !value) {
                input?.focus();
                return;
            }

            const existingOption = [...container.querySelectorAll('input')].some(
                (option) => option.value.toLocaleLowerCase('fr') === value.toLocaleLowerCase('fr')
            );
            if (existingOption) {
                input.value = '';
                return;
            }

            const label = document.createElement('label');
            label.className = optionType === 'type' ? 'choice-pill' : 'technology-option';
            const option = document.createElement('input');
            option.type = optionType === 'type' ? 'radio' : 'checkbox';
            option.name = optionType === 'type' ? 'type' : 'technos[]';
            option.value = value;
            option.checked = true;
            const text = document.createElement('span');
            text.textContent = value;
            label.append(option, text);
            container.append(label);
            input.value = '';
            input.focus();
        });
    });

    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) event.preventDefault();
        });
    });
});
