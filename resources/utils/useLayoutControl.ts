// src/utils/useLayoutControl.ts
import { inject, ref, watchEffect } from 'vue'

/* How to use.  Add the following to the component
import { onMounted, onUnmounted } from 'vue';
import { useLayoutControl} from "@/utils/useLayoutControl";

const { setNavVisibility, setFullWidth } = useLayoutControl();

onMounted(() => {
    setNavVisibility(false);
    setFullWidth(true);
}

onUnmounted(() => {
    setNavVisibility(true);
    setFullWidth(false);
}
 */

export function useLayoutControl() {
    const showNav = inject('showNav', ref(true));
    const useFullWidth = inject('useFullWidth', ref(false));

    function setNavVisibility(visible: boolean) {
        if (showNav) showNav.value = visible
    }

    function setFullWidth(full: boolean) {
        if (useFullWidth) useFullWidth.value = full
    }

    return {
        setNavVisibility,
        setFullWidth
    }
}
