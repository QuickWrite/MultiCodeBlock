/**
 * This file is part of MultiCodeBlock.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/* Switching between languages */
const tabButtons = document.getElementsByClassName('tab-button');

for (let i = 0; i < tabButtons.length; i++) {
    tabButtons[i].addEventListener("click", () => {
        const button = tabButtons[i];
        const outer = (button.classList.contains('outer') ? 'outer' : 'inner');

        const sidebar = button.parentElement;
        const tabContainer = sidebar.parentElement;
        const tabNumber = button.dataset.forTab;
        const activateTab = tabContainer.querySelector(`.${outer}.tab-content[data-tab="${tabNumber}"]`);

        {
            const innerTabButtons = sidebar.getElementsByClassName(outer + ' tab-button');
            for (let i = 0; i < innerTabButtons.length; i++) {
                innerTabButtons[i].classList.remove('tb-active');
            }
        }

        {
            const innerTabContents = tabContainer.getElementsByClassName(outer + ' tab-content');
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
let lockedElems = null;
let firstClick = true;

let currentElem = null;

for(let i = 0; i < second.length; i++) {
    second[i].addEventListener('mouseover', () => {
        second[i].classList.add('onHover');

        currentElem = second[i];
    });

    second[i].addEventListener('mouseout', () => {
        currentElem = null;

        if(lockedElems !== null)
            return;
        
        second[i].classList.remove('onHover');
    });

    second[i].addEventListener('mousedown', () => {
        lockedElems = second[i].parentElement.parentElement.getElementsByClassName('first');

        for(let i = 0; i < lockedElems.length; ++i) {
            lockedElems[i].classList.add('locked');
        }
    });
}

document.addEventListener('click', (event) => {
    if(lockedElems === null)
        return;
    
    if(firstClick) {
        firstClick = false;
        return;
    }

    for(let i = 0; i < lockedElems.length; ++i) {
        lockedElems[i].classList.remove('locked');
    }

    lockedElems = null;

    for(let i = 0; i < second.length; ++i) {
        second[i].classList.remove('onHover');
    }

    firstClick = true;

    currentElem?.classList.add('onHover');
});

/* Copy */

let copyElems = document.getElementsByClassName('copy');

const messages = copyElems[0].getElementsByClassName('tooltip')[0].innerHTML.split('|');

for(let i = 0; i < copyElems.length; i++) {
    copyElems[i].addEventListener('click', async () => {
        const elem = copyElems[i];
        elem.classList.remove('anim');
        setTimeout(() => {elem.classList.add('anim');},1);

        const tooltip = elem.getElementsByClassName('tooltip')[0];

        let fail = false;

        if (navigator.clipboard) {
            const code = elem.parentElement.getElementsByClassName('outer tc-active')[0].getElementsByClassName('inner tc-active')[0].getElementsByClassName('first');
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

        if(!fail) {
            // Success
            tooltip.innerText = messages[0];
        } else {
            // Fail
            tooltip.innerText = messages[1];
        }

        tooltip.style.visibility = 'visible';

        setTimeout(() => {
            tooltip.style.visibility = 'hidden';
        }, 2000);
    });
}
