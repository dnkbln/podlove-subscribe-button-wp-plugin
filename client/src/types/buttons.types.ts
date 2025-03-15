export interface SubscribeButton {
    id: string
    name: string | null
    title: string | null
    subtitle: string | null
    description: string | null
    cover: string | null
    feeds: Feed[]
}

export interface Feed {
    url: string | null
    itunesfeedid: string | null
    format: string | null
}