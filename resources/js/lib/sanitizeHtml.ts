const FORBIDDEN_TAGS = [
    'script',
    'iframe',
    'object',
    'embed',
    'link',
    'meta',
    'base',
    'form',
    'input',
    'button',
    'textarea',
];

const DANGEROUS_URL_PATTERN = /^(?:javascript:|data:\s*text\/html)/i;

function stripDangerousAttributes(element: Element): void {
    for (const attribute of Array.from(element.attributes)) {
        const name = attribute.name.toLowerCase();
        const value = attribute.value.trim();

        if (name.startsWith('on')) {
            element.removeAttribute(attribute.name);
            continue;
        }

        if (
            (name === 'href' || name === 'src' || name === 'xlink:href') &&
            DANGEROUS_URL_PATTERN.test(value)
        ) {
            element.removeAttribute(attribute.name);
            continue;
        }
    }
}

function sanitizeUsingDomParser(rawHtml: string): string {
    const parser = new DOMParser();
    const doc = parser.parseFromString(rawHtml, 'text/html');

    FORBIDDEN_TAGS.forEach((tag) => {
        doc.querySelectorAll(tag).forEach((node) => node.remove());
    });

    doc.querySelectorAll('*').forEach((element) => {
        stripDangerousAttributes(element);
    });

    return doc.body.innerHTML;
}

function sanitizeFallback(rawHtml: string): string {
    return rawHtml
        .replace(/<script\b[^>]*>[\s\S]*?<\/script>/gi, '')
        .replace(/\son\w+\s*=\s*(['"]).*?\1/gi, '')
        .replace(/\s(href|src|xlink:href)\s*=\s*(['"])\s*javascript:[^'"]*\2/gi, '');
}

export function sanitizeRichHtml(rawHtml: string | null | undefined): string {
    if (!rawHtml) {
        return '';
    }

    if (typeof DOMParser === 'undefined') {
        return sanitizeFallback(rawHtml);
    }

    return sanitizeUsingDomParser(rawHtml);
}
