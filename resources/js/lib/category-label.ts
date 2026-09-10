const labels: Record<string, string> = {
    writing: '筆記具',
    notebook: 'ノート',
    desk: 'デスク',
    storage: '収納',
    tools: 'ツール',
};

export function categoryLabel(category: { name: string; slug: string }): string {
    return labels[category.slug] ?? category.name;
}
