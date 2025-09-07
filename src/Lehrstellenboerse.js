import {
  useState,
  useEffect,
} from "@wordpress/element"

import {
  createColumnHelper,
} from "@tanstack/react-table"

const columnHelper = createColumnHelper()

const columns = [
  columnHelper.accessor("name", {
    id: "name",
    header: () => "Betrieb/Ansprechpartner",
    cell: info => info.getValue(),
  }),
  columnHelper.accessor("plz", {
    id: "plz",
    header: () => "PLZ",
    cell: info => info.getValue(),
  }),
  columnHelper.accessor("city", {
    id: "city",
    header: () => "Ort",
    cell: info => info.getValue(),
  }),
  columnHelper.group({
    id: "contact",
    header: () => "Kontakt",
    columns: [
      columnHelper.accessor("contact.phone", {
        id: "phone",
        cell: info => <a href="tel:${info.getValue()}">📞${info.getValue()}</a>, 
      }),
      columnHelper.accessor("contact.cell", {
        id: "phone",
        cell: info => <a href="tel:${info.getValue()}">📱${info.getValue()}</a>,
      }),
      columnHelper.accessor("contact.email", {
        id: "email",
        cell: info => <a href="mailto:${info.getValue()}">📧${info.getValue()}</a>,
      }),
    ],
  }),
  columnHelper.group({
    id: "positions",
    columns: {
    },
  }),
]

export default function Lehrstellenboerse() {
  const [results, setResults] = useState([])
  useEffect(() => {
    (async () => {
      const res = await fetch("/?rest_route=/lehrstellenboerse/v1/betriebe")
      setResults(await res.json())
    })()
    return () => {}
  }, [])
}
