import { Autocomplete, Checkbox, FormControlLabel, Grid, TextField, } from '@mui/material'
import {
  createRoot,
  useState
} from '@wordpress/element'
import List from 'list.js'

const list = new List("lehrstellenboerse", {
  valueNames: [ "name", "plz", "city", "positions", "internships" ],
});

const cities = [...new Set(list.items.map(it => it.values().city).sort())]
const jobTitles = [
  "Maurer*in",
  "Zimmerer*in",
  "Fliesenleger*in",
  "Stahl- und Betonbauer*in",
  "Kanalbauer*in",
  "Straßenbauer*in",
  "HBF Maurerarbeiten",
  "ABF Zimmerarbeiten",
  "ABF Fliesenarbeiten",
  "HBF Betonarbeiten",
  "TBF Straßenbauarbeiten",
  "TBF Kanalbauarbeiten",
]

function Filters() {
  const [searchingJob, setSearchingJob] = useState(true)
  const [searchingInternship, setSearchingInternship] = useState(true)
  const [places, setPlaces] = useState([])
  const [jobs, setJobs] = useState([])

  list.filter(item => {
    const { city, positions, internships } = item.values()
    if (places.length != 0 && !places.find(it => it == city))
      return false

    if (jobs.length == 0) {
      return (searchingJob && positions.length != 0) ||
        (searchingInternship && internships.length != 0)
    } else {
      if (searchingJob) {
        for (const job of jobs) {
          if (positions.includes(job))
            return true
        }
      }

      if (searchingInternship) {
        for (const job of jobs) {
          if (internships.includes(job))
            return true
        }
      }
    }

    return false
  })

  return <Grid container spacing={.5}>
    <Grid size={12}>
      Ich suche:
    </Grid>
    <Grid size={{ xs: 12, sm: 5, lg: 2 }}>
      <FormControlLabel 
        control=<Checkbox 
          defaultChecked
          onChange={e => setSearchingJob(e.target.checked)}
        />
        label="einen Ausbildungsplatz"
      />
    </Grid>
    <Grid size={{ xs: 12, sm: 5, lg: 10 }}>
      <FormControlLabel 
        control=<Checkbox 
          defaultChecked
          onChange={e => setSearchingInternship(e.target.checked)}
        />
        label="ein Praktikum"
      />
    </Grid>
    <Grid size={{ xs: 12, sm: 4 }}>
      <Autocomplete 
        multiple 
        options={cities}
        renderInput={(params) => <TextField {...params} label="Ort" />}
        onChange={(_, value) => setPlaces(value)}
      />
    </Grid>
    <Grid size={{ xs: 12, sm: 8 }}>
      <Autocomplete 
        multiple 
        options={jobTitles}
        renderInput={(params) => <TextField {...params} label="Berufe" />}
        onChange={(_, value) => setJobs(value)}
      />
    </Grid>
  </Grid>
}

createRoot(
  document.getElementById("lehrstellenboerse-filter")
).render(<Filters/>)
