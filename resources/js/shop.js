/**
 * レジの「お金をだす」ところ。
 *
 * こどもが じぶんで おかねを えらんで、
 * だしたがく − ねだん = おつり が すぐ見えるようにする。
 */

const formatYen = (value) => new Intl.NumberFormat('ja-JP').format(value);

function setupCheckout(root) {
    const total = Number(root.dataset.total);
    const wallet = Number(root.dataset.wallet);

    const paidInput = root.querySelector('[data-paid-input]');
    const paidDisplay = root.querySelector('[data-paid-display]');
    const changeDisplay = root.querySelector('[data-change-display]');
    const statusDisplay = root.querySelector('[data-status-display]');
    const submitButton = root.querySelector('[data-submit]');
    const stack = root.querySelector('[data-stack]');

    /** @type {number[]} だした おかね を だした じゅんに ためる */
    let handed = [];

    const render = () => {
        const paid = handed.reduce((sum, value) => sum + value, 0);
        const shortage = total - paid;
        const enough = shortage <= 0;

        paidInput.value = paid;
        paidDisplay.textContent = formatYen(paid);
        changeDisplay.textContent = enough ? formatYen(-shortage) : '？';

        statusDisplay.textContent = enough
            ? 'ぴったり はらえるよ！'
            : `あと ${formatYen(shortage)}えん たりないよ`;
        statusDisplay.classList.toggle('bg-pop-green', enough);
        statusDisplay.classList.toggle('bg-pop-orange', !enough);

        submitButton.disabled = !enough;
        submitButton.classList.toggle('opacity-50', !enough);
        submitButton.classList.toggle('cursor-not-allowed', !enough);
        submitButton.classList.toggle('block-press', enough);

        stack.innerHTML = '';
        handed.forEach((value) => {
            const chip = document.createElement('li');
            chip.className =
                'rounded-xl border-4 border-ink bg-pop-yellow px-3 py-1 text-base font-black animate-pop-in';
            chip.textContent = `${formatYen(value)}えん`;
            stack.append(chip);
        });
    };

    root.querySelectorAll('[data-denomination]').forEach((button) => {
        button.addEventListener('click', () => {
            const value = Number(button.dataset.denomination);
            const paid = handed.reduce((sum, amount) => sum + amount, 0);

            if (paid + value > wallet) {
                return;
            }

            handed.push(value);
            render();
        });
    });

    root.querySelector('[data-undo]')?.addEventListener('click', () => {
        handed.pop();
        render();
    });

    root.querySelector('[data-reset]')?.addEventListener('click', () => {
        handed = [];
        render();
    });

    render();
}

document.querySelectorAll('[data-checkout]').forEach(setupCheckout);
