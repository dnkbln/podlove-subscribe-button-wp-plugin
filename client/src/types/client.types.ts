export interface Client {
    id: number
    title: string | null
    platform: string[] | string | null
    type: string[] | string | null
    logo: string | null
    call_schema: string | null
    button_id: number
    podcast_id?: string | null
}

export type ClientAddSelection =
    | { type: 'all' }
    | { type: 'client'; client: Client };
