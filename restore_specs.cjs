const fs = require('fs');

const origPath = 'c:/wira/projek/web/zlm-id/original_detail.blade.php';
const currentPath = 'c:/wira/projek/web/zlm-id/resources/views/landing/detail.blade.php';

let origContent = fs.readFileSync(origPath, 'utf8');
let currentContent = fs.readFileSync(currentPath, 'utf8');

// Extract the specs section from the original file
const startMarker = '        <!-- Technical Specifications Table Section -->';
const endMarker = '        <!-- Kelebihan & Kekurangan Section -->';

const startIndex = origContent.indexOf(startMarker);
const endIndex = origContent.indexOf(endMarker);

if (startIndex !== -1 && endIndex !== -1) {
    let specsSection = origContent.substring(startIndex, endIndex);
    
    // In current file, we want to insert it right before "        <!-- Similar Laptops Section -->"
    const insertMarker = '        <!-- Similar Laptops Section -->';
    const insertIndex = currentContent.indexOf(insertMarker);
    
    if (insertIndex !== -1) {
        currentContent = currentContent.substring(0, insertIndex) + specsSection + currentContent.substring(insertIndex);
        fs.writeFileSync(currentPath, currentContent, 'utf8');
        console.log('Successfully injected specs section.');
    } else {
        console.log('Could not find insert marker in current file.');
    }
} else {
    console.log('Could not find specs section in original file.');
}
