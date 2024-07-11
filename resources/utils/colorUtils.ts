// utils/colorUtils.ts

export function hexToRgba(hex: string | undefined | null, alpha = 10): string {
    if (!hex) {
        // Return a default rgba value or handle the error as needed
        return `rgba(0, 0, 0, ${alpha / 100})`; // Default to black with given alpha
    }

    // Remove the leading '#' if it is present
    hex = hex.replace(/^#/, '');

    // Convert 3-digit hex to 6-digit hex
    if (hex.length === 3) {
        hex = hex
            .split('')
            .map((char) => char + char)
            .join('');
    }

    // Parse the r, g, b values
    const intVal = parseInt(hex, 16);
    const r = (intVal >> 16) & 255;
    const g = (intVal >> 8) & 255;
    const b = intVal & 255;

    // Convert alpha to the range [0, 1]
    alpha = alpha / 100;

    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}
