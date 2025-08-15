import {
  useState,
  useEffect,
} from "@wordpress/element"

export default function Lehrstellenboerse() {
  const [results, setResults] = useState([])
  useEffect(() => {
    (async () => {
      const res = await fetch("/?rest_route=/lehrstellenboerse/v1/betriebe")
      setResults(await res.json())
    })()
    return () => {}
  }, [])
  console.log(results)
}
