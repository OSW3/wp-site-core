export function initTabs(root = document) {
  root.querySelectorAll('[data-tabs]').forEach((component) => {
    if (component.dataset.tabsReady) return;
    const list = component.querySelector('[data-tab-list]');
    const tabs = Array.from(list?.querySelectorAll('[data-tab]') || []);
    const panels = Array.from(component.querySelectorAll(':scope > .tabs__panels > [data-tab-panel]'));
    const enabled = tabs.filter((tab) => !tab.hasAttribute('data-tab-disabled'));
    if (!list || !enabled.length || tabs.length !== panels.length) {
      console.error('Tabs requires matching tabs and panels and at least one enabled tab.', component);
      return;
    }
    component.dataset.tabsReady = 'true';
    list.setAttribute('role', 'tablist');
    list.setAttribute('aria-orientation', component.dataset.orientation);
    const activate = (tab, focus = false) => {
      if (!enabled.includes(tab)) return;
      tabs.forEach((item, index) => {
        const selected = item === tab;
        item.setAttribute('aria-selected', String(selected));
        item.tabIndex = selected ? 0 : -1;
        panels[index].hidden = !selected;
      });
      if (focus) tab.focus();
      component.dispatchEvent(new CustomEvent('tabs:changed', { bubbles: true, detail: { index: tabs.indexOf(tab), panel: panels[tabs.indexOf(tab)] } }));
    };
    tabs.forEach((tab, index) => {
      tab.setAttribute('role', 'tab');
      tab.setAttribute('aria-controls', panels[index].id);
      panels[index].setAttribute('role', 'tabpanel');
      panels[index].tabIndex = 0;
      tab.addEventListener('click', (event) => {
        event.preventDefault();
        activate(tab);
      });
      tab.addEventListener('keydown', (event) => {
        if (!enabled.includes(tab)) return;
        const vertical = component.dataset.orientation === 'vertical';
        const previous = vertical ? 'ArrowUp' : 'ArrowLeft';
        const next = vertical ? 'ArrowDown' : 'ArrowRight';
        let target;
        if (event.key === previous || event.key === next) {
          const step = event.key === next ? 1 : -1;
          target = enabled[(enabled.indexOf(tab) + step + enabled.length) % enabled.length];
        } else if (event.key === 'Home') target = enabled[0];
        else if (event.key === 'End') target = enabled[enabled.length - 1];
        else if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault();
          activate(tab);
          return;
        } else return;
        event.preventDefault();
        if (component.dataset.activation === 'manual') {
          tabs.forEach((item) => { item.tabIndex = item === target ? 0 : -1; });
          target.focus();
        } else activate(target, true);
      });
    });
    const hashTab = tabs.find((tab, index) => `#${panels[index].id}` === window.location.hash && enabled.includes(tab));
    const initial = tabs[Number(component.dataset.active)];
    activate(hashTab || (enabled.includes(initial) ? initial : enabled[0]));
  });
}

document.addEventListener('DOMContentLoaded', () => initTabs());
