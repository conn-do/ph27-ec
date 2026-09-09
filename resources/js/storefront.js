const initializePostalLookup = () => {
    document.querySelectorAll('[data-postal-lookup]').forEach((form) => {
        const postalCodeInput = form.querySelector('[data-postal-code]');
        const addressInput = form.querySelector('[data-postal-address]');
        const status = form.querySelector('[data-postal-status]');
        const lookupUrl = form.dataset.postalLookupUrl;

        if (!postalCodeInput || !addressInput || !status || !lookupUrl) {
            return;
        }

        let lookupTimer;
        let requestNumber = 0;

        const lookupAddress = async () => {
            const postalCode = postalCodeInput.value.replace(/\D/g, '');

            if (postalCode.length !== 7) {
                status.textContent = '';

                return;
            }

            postalCodeInput.value = `${postalCode.slice(0, 3)}-${postalCode.slice(3)}`;
            const currentRequest = ++requestNumber;
            status.textContent = '住所を検索しています…';

            try {
                const response = await fetch(
                    lookupUrl.replace(
                        '__POSTAL_CODE__',
                        encodeURIComponent(postalCode),
                    ),
                    { headers: { Accept: 'application/json' } },
                );
                const data = await response.json();

                if (currentRequest !== requestNumber) {
                    return;
                }

                if (
                    !response.ok ||
                    typeof data.address !== 'string' ||
                    data.address === ''
                ) {
                    throw new Error(
                        data.message ?? '住所を取得できませんでした。',
                    );
                }

                addressInput.value = data.address;
                addressInput.dispatchEvent(
                    new Event('input', { bubbles: true }),
                );
                status.textContent =
                    '住所を自動入力しました。番地・建物名を追記してください。';
            } catch (error) {
                if (currentRequest !== requestNumber) {
                    return;
                }

                status.textContent =
                    error instanceof Error
                        ? error.message
                        : '住所を取得できませんでした。手動で入力してください。';
            }
        };

        postalCodeInput.addEventListener('input', () => {
            clearTimeout(lookupTimer);

            if (postalCodeInput.value.replace(/\D/g, '').length !== 7) {
                requestNumber++;
                status.textContent = '';

                return;
            }

            lookupTimer = setTimeout(lookupAddress, 300);
        });

        postalCodeInput.addEventListener('blur', () => {
            clearTimeout(lookupTimer);
            lookupAddress();
        });
    });
};

const initializePaymentOptions = () => {
    document.querySelectorAll('[data-payment-options]').forEach((options) => {
        const paymentOptions = options.querySelectorAll(
            '[data-payment-option]',
        );

        const updatePaymentOptions = () => {
            const selectedMethod = options.querySelector(
                'input[name="payment_method"]:checked',
            )?.value;

            paymentOptions.forEach((option) => {
                const radio = option.querySelector(
                    'input[name="payment_method"]',
                );
                const isSelected = radio?.value === selectedMethod;

                option.classList.toggle('is-selected', isSelected);

                option
                    .querySelectorAll('[data-payment-panel]')
                    .forEach((panel) => {
                        panel.hidden =
                            panel.dataset.paymentPanel !== selectedMethod;
                    });
            });
        };

        options.addEventListener('change', updatePaymentOptions);
        updatePaymentOptions();
    });
};

const initializeCardInputFormatting = () => {
    document.querySelectorAll('[data-card-number]').forEach((input) => {
        input.addEventListener('input', () => {
            input.value = input.value
                .replace(/\D/g, '')
                .slice(0, 16)
                .replace(/(.{4})/g, '$1 ')
                .trim();
        });
    });

    document.querySelectorAll('[data-card-expiry]').forEach((input) => {
        input.addEventListener('input', () => {
            const digits = input.value.replace(/\D/g, '').slice(0, 4);
            input.value =
                digits.length > 2
                    ? `${digits.slice(0, 2)} / ${digits.slice(2)}`
                    : digits;
        });
    });

    document.querySelectorAll('[data-card-security-code]').forEach((input) => {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/\D/g, '').slice(0, 4);
        });
    });
};

const initializeStorefront = () => {
    initializePostalLookup();
    initializePaymentOptions();
    initializeCardInputFormatting();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeStorefront);
} else {
    initializeStorefront();
}
