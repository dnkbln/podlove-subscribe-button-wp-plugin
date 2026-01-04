export interface Client {
    id: string
    title: string | null
    platform: string[] | string | null
    type: string[] | string | null
    logo: string | null
    call_schema: string | null
}
