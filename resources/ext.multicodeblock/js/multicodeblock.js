/* Switching between languages */
const tabButtons = document.getElementsByClassName('tab-button');

for (let i = 0; i < tabButtons.length; i++) {
    tabButtons[i].addEventListener("click", () => {
        const button = tabButtons[i];
        const sidebar = button.parentElement;
        const tabContainer = sidebar.parentElement;
        const tabNumber = button.dataset.forTab;
        const activateTab = tabContainer.querySelector(`.tab-content[data-tab="${tabNumber}"]`);

        {
            const innerTabButtons = sidebar.getElementsByClassName('tab-button');
            for (let i = 0; i < innerTabButtons.length; i++) {
                innerTabButtons[i].classList.remove('tb-active');
            }
        }

        {
            const innerTabContents = tabContainer.getElementsByClassName('tab-content');
            for (let i = 0; i < innerTabContents.length; i++) {
                innerTabContents[i].classList.remove('tc-active');
            }
        }

        button.classList.add('tb-active');
        activateTab.classList.add('tc-active');
    });
}

/* Hover mechanics */

const second = document.getElementsByClassName('second');
let lockedElems = [];

let secondMouseDown = false;
let lastMouseUp = false;
let lastMouseDown = false;
let lockClick = true;

for(let i = 0; i < second.length; i++) {
    second[i].addEventListener('mouseover', () => {
        if(!lastMouseUp) {
            second[i].classList.add('onHover');
        }

        if(secondMouseDown) {
            let secondElem = second[i].parentElement.getElementsByClassName('first')[0];
            secondElem.classList.add('locked');
            lockedElems.push(secondElem);
        }
    });

    second[i].addEventListener('mouseout', () => {
        if(!secondMouseDown && !lastMouseUp) {
            second[i].classList.remove('onHover');
        }
    });

    second[i].addEventListener('mousedown', () => {
        secondMouseDown = true;
        lastMouseDown = true;
    });

    second[i].addEventListener('mouseup', () => {
        secondMouseDown = false;
        lastMouseUp = true;
        setTimeout(function(){lockClick = false;},5);
    });
}

document.addEventListener('click', () => {
    if(!lockClick) {
        lastMouseUp = false;
        for(let i = 0;i < lockedElems.length; i++) {
            lockedElems[i].classList.remove('locked');
        }
        for(let i = 0; i < second.length; i++) {
            second[i].classList.remove('onHover');
        }

        lockClick = true;
    }
});

/* Copy */

let copyElems = document.getElementsByClassName('copy');

for(let i = 0; i < copyElems.length; i++) {
    copyElems[i].addEventListener('click', async () => {
        const elem = copyElems[i];
        elem.classList.remove('anim');
        setTimeout(() => {elem.classList.add('anim');},1);

        const tooltip = elem.getElementsByClassName('tooltip')[0];

        let fail = false;

        if (navigator.clipboard) {
            const code = elem.parentElement.getElementsByClassName('tc-active')[0].getElementsByClassName('first');
            let text = '';
            for(let i = 0; i < code.length; i++) {
                text += code[i].innerText;
            }

            try {
                await navigator.clipboard.writeText(text);
            } catch (err) {
                fail = true;
            }
        } else {
            fail = true;
        }

        if(fail) {
            tooltip.innerText = 'Failed to copy!';
        } else {
            tooltip.innerText = 'Copied!';
        }

        tooltip.style.visibility = 'visible';
        setTimeout(() => {tooltip.style.visibility = 'hidden';},2000);
    });
}
